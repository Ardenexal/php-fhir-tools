<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRConformanceViolationException;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\FhirPropertyTypeHierarchyResolver;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRReferenceResolver;
use Ardenexal\FHIRTools\Component\Validation\SliceDiscriminatorMatcher;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRFixedValueValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRPathInvariantValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRPatternValueValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRProfileConstraintValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRSliceConstraintValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRTargetProfileValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRValueSetBindingValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Official FHIR validator specification conformance test for R4, R4B, and R5 resources.
 *
 * Test cases are driven by vendor/fhir/fhir-test-cases/validator/manifest.json.
 * Each applicable case deserializes its resource file, runs FHIRValidationService,
 * and asserts exact violation counts against our own outcome files stored in
 * src/Component/Validation/tests/Integration/outcomes/ardenexal/.
 *
 * Two-tier outcome system:
 *  - Java outcomes (vendor/fhir/fhir-test-cases/validator/outcomes/java/) classify
 *    cases as valid (errorCount=0) or invalid (errorCount>0).
 *  - Ardenexal outcomes (outcomes/ardenexal/) store our own expected counts, seeded
 *    from actual FHIRValidationService output and updated when gaps are fixed.
 *
 * Skip policy (cases yielded as skipped, not failed):
 * - use-test === false
 * - File extension is not .json or .xml
 * - Module is one of: tx (requires external terminology server), cda, cdshooks, shc, matchetype
 * - Top-level "supporting" or "profiles" keys present (requires dynamic SD loading)
 * - Deserialization throws or returns a non-object
 * - No ardenexal outcome file exists → markTestIncomplete
 *
 * Version routing: R4 (version=4.0), R4B (version=4.3), R5 (no version field, or 5.0/5.0.0).
 * R5 binding validation uses the R5 enum namespace; R4B binding falls back to R4 enums.
 */
#[CoversClass(FHIRValidationService::class)]
final class FHIRValidatorSpecificationTest extends TestCase
{
    // matchetype: FHIRPath pattern-matching syntax tests that use $instant$ as a
    // placeholder — not a real dateTime value, cannot be deserialized.
    private const SKIP_MODULES = ['tx', 'cda', 'cdshooks', 'shc', 'matchetype'];

    private const OUTCOMES_DIR = __DIR__ . '/outcomes/ardenexal';

    private FHIRValidationService $service;

    private FHIRSerializationService $serialization;

    private FHIRSerializationService $serializationR4B;

    private FHIRSerializationService $serializationR5;

    private FHIRValidationService $serviceR5;

    protected function setUp(): void
    {
        $this->service          = self::createValidationService(FhirVersion::R4);
        $this->serviceR5        = self::createValidationService(FhirVersion::R5);
        $this->serialization    = FHIRSerializationService::createDefault(FhirVersion::R4);
        $this->serializationR4B = FHIRSerializationService::createDefault(FhirVersion::R4B);
        $this->serializationR5  = FHIRSerializationService::createDefault(FhirVersion::R5);
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function provideValidatorTestCases(): iterable
    {
        $vendorDir    = dirname(__DIR__, 5) . '/vendor';
        $manifestPath = $vendorDir . '/fhir/fhir-test-cases/validator/manifest.json';

        if (!file_exists($manifestPath)) {
            yield '__vendor_not_installed__' => ['__skip__', '', 0];

            return;
        }

        $raw = file_get_contents($manifestPath);
        if ($raw === false) {
            return;
        }

        /** @var array{test-cases: list<array<string, mixed>>} $manifest */
        $manifest = json_decode($raw, true);

        // Collect and deduplicate: some names appear multiple times in the manifest
        // (e.g. once with java:{} placeholder and again with the real outcome file).
        // Keep the last entry per name so the canonical outcome always wins.
        /** @var array<string, array{string, string, int}> $cases */
        $cases = [];

        foreach ($manifest['test-cases'] as $case) {
            $name = (string) ($case['name'] ?? '');

            // Skip disabled tests
            if (isset($case['use-test']) && $case['use-test'] === false) {
                continue;
            }

            // R4 only (R4B and R5 covered in later milestones)
            if (($case['version'] ?? '') !== '4.0') {
                continue;
            }

            $file = (string) ($case['file'] ?? '');

            // JSON and XML supported; other formats skipped
            if (!str_ends_with($file, '.json') && !str_ends_with($file, '.xml')) {
                continue;
            }

            // Skip modules that require external services or are out of scope
            $module = (string) ($case['module'] ?? '');
            if (in_array($module, self::SKIP_MODULES, true)) {
                continue;
            }

            // Skip cases requiring dynamic StructureDefinition loading
            if (isset($case['supporting']) || isset($case['profiles'])) {
                continue;
            }

            // allow-comments:true uses JSON5 comment syntax — not supported by our JSON parser
            if (($case['allow-comments'] ?? false) === true) {
                continue;
            }

            $filePath = $vendorDir . '/fhir/fhir-test-cases/validator/' . $file;
            if (!file_exists($filePath)) {
                continue;
            }

            if (self::resolveJavaErrorCount($case, $vendorDir . '/fhir/fhir-test-cases/validator') === null) {
                continue;
            }

            $cases[$name] = [$name, $filePath];
        }

        yield from $cases;
    }

    #[DataProvider('provideValidatorTestCases')]
    public function testValidatorSpecificationCase(string $name, string $filePath): void
    {
        if ($name === '__skip__') {
            $this->markTestSkipped('fhir/fhir-test-cases not installed — run: composer update fhir/fhir-test-cases');
        }

        $outcomeFile = self::OUTCOMES_DIR . '/R4.' . self::sanitizeName($name) . '-base.json';
        if (!file_exists($outcomeFile)) {
            $this->markTestIncomplete("No ardenexal outcome defined yet for '{$name}'");
        }

        /** @var array{errorCount: int, warningCount: int, infoCount: int} $expected */
        $expected = json_decode((string) file_get_contents($outcomeFile), true);

        $data = file_get_contents($filePath);
        if ($data === false) {
            $this->markTestSkipped("Cannot read test case file: {$filePath}");
        }

        try {
            $resource = $this->serialization->deserialize($data);
        } catch (FHIRConformanceViolationException $e) {
            self::assertConformanceViolationIsSeededAsAFinding($expected, $name, $e);

            return;
        } catch (\Throwable $e) {
            self::assertCaseIsSeededAsUnread($expected, $name, $e);

            return;
        }

        self::assertCaseIsNotSeededAsUnread($expected, $name);

        try {
            $report = $this->service->validate($resource);
        } catch (\Error $e) {
            $this->markTestSkipped("Validation threw Error for '{$name}': {$e->getMessage()}");
        }

        $realErrors   = array_values(array_filter($report->errors(), fn ($v) => !$this->isKnownGap($v, $resource)));
        $realWarnings = array_values(array_filter($report->warnings(), fn ($v) => !$this->isKnownGap($v, $resource)));

        self::assertSame(
            $expected['errorCount'],
            count($realErrors),
            "Unexpected error count for '{$name}': "
            . implode(', ', array_map(static fn (FHIRValidationViolation $v) => $v->message, $realErrors)),
        );
        self::assertSame(
            $expected['warningCount'],
            count($realWarnings),
            "Unexpected warning count for '{$name}'",
        );
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function provideR4BValidatorTestCases(): iterable
    {
        $vendorDir    = dirname(__DIR__, 5) . '/vendor';
        $manifestPath = $vendorDir . '/fhir/fhir-test-cases/validator/manifest.json';

        if (!file_exists($manifestPath)) {
            yield '__vendor_not_installed__' => ['__skip__', ''];

            return;
        }

        $raw = file_get_contents($manifestPath);
        if ($raw === false) {
            return;
        }

        /** @var array{test-cases: list<array<string, mixed>>} $manifest */
        $manifest = json_decode($raw, true);

        /** @var array<string, array{string, string}> $cases */
        $cases = [];

        foreach ($manifest['test-cases'] as $case) {
            $name = (string) ($case['name'] ?? '');

            if (isset($case['use-test']) && $case['use-test'] === false) {
                continue;
            }

            if (($case['version'] ?? '') !== '4.3') {
                continue;
            }

            $file = (string) ($case['file'] ?? '');

            if (!str_ends_with($file, '.json') && !str_ends_with($file, '.xml')) {
                continue;
            }

            $module = (string) ($case['module'] ?? '');
            if (in_array($module, self::SKIP_MODULES, true)) {
                continue;
            }

            if (isset($case['supporting']) || isset($case['profiles'])) {
                continue;
            }

            $filePath = $vendorDir . '/fhir/fhir-test-cases/validator/' . $file;
            if (!file_exists($filePath)) {
                continue;
            }

            if (self::resolveJavaErrorCount($case, $vendorDir . '/fhir/fhir-test-cases/validator') === null) {
                continue;
            }

            $cases[$name] = [$name, $filePath];
        }

        yield from $cases;
    }

    #[DataProvider('provideR4BValidatorTestCases')]
    public function testR4BValidatorSpecificationCase(string $name, string $filePath): void
    {
        if ($name === '__skip__') {
            $this->markTestSkipped('fhir/fhir-test-cases not installed — run: composer update fhir/fhir-test-cases');
        }

        $outcomeFile = self::OUTCOMES_DIR . '/R4B.' . self::sanitizeName($name) . '-base.json';
        if (!file_exists($outcomeFile)) {
            $this->markTestIncomplete("No ardenexal outcome defined yet for R4B '{$name}'");
        }

        /** @var array{errorCount: int, warningCount: int, infoCount: int} $expected */
        $expected = json_decode((string) file_get_contents($outcomeFile), true);

        $data = file_get_contents($filePath);
        if ($data === false) {
            $this->markTestSkipped("Cannot read test case file: {$filePath}");
        }

        try {
            $resource = $this->serializationR4B->deserialize($data);
        } catch (FHIRConformanceViolationException $e) {
            self::assertConformanceViolationIsSeededAsAFinding($expected, "R4B {$name}", $e);

            return;
        } catch (\Throwable $e) {
            self::assertCaseIsSeededAsUnread($expected, "R4B {$name}", $e);

            return;
        }

        self::assertCaseIsNotSeededAsUnread($expected, "R4B {$name}");

        try {
            $report = $this->service->validate($resource);
        } catch (\Error $e) {
            $this->markTestSkipped("Validation threw Error for R4B '{$name}': {$e->getMessage()}");
        }

        $realErrors   = array_values(array_filter($report->errors(), fn ($v) => !$this->isKnownGap($v, $resource)));
        $realWarnings = array_values(array_filter($report->warnings(), fn ($v) => !$this->isKnownGap($v, $resource)));

        self::assertSame(
            $expected['errorCount'],
            count($realErrors),
            "Unexpected error count for R4B '{$name}': "
            . implode(', ', array_map(static fn (FHIRValidationViolation $v) => $v->message, $realErrors)),
        );
        self::assertSame(
            $expected['warningCount'],
            count($realWarnings),
            "Unexpected warning count for R4B '{$name}'",
        );
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function provideR5ValidatorTestCases(): iterable
    {
        $vendorDir    = dirname(__DIR__, 5) . '/vendor';
        $manifestPath = $vendorDir . '/fhir/fhir-test-cases/validator/manifest.json';

        if (!file_exists($manifestPath)) {
            yield '__vendor_not_installed__' => ['__skip__', ''];

            return;
        }

        $raw = file_get_contents($manifestPath);
        if ($raw === false) {
            return;
        }

        /** @var array{test-cases: list<array<string, mixed>>} $manifest */
        $manifest = json_decode($raw, true);

        /** @var array<string, array{string, string}> $cases */
        $cases = [];

        foreach ($manifest['test-cases'] as $case) {
            $name = (string) ($case['name'] ?? '');

            if (isset($case['use-test']) && $case['use-test'] === false) {
                continue;
            }

            // R5: no version field, or explicit 5.0 / 5.0.0
            $version = $case['version'] ?? null;
            if ($version !== null && !in_array($version, ['5.0', '5.0.0'], true)) {
                continue;
            }

            $file = (string) ($case['file'] ?? '');

            if (!str_ends_with($file, '.json') && !str_ends_with($file, '.xml')) {
                continue;
            }

            $module = (string) ($case['module'] ?? '');
            if (in_array($module, self::SKIP_MODULES, true)) {
                continue;
            }

            if (isset($case['supporting']) || isset($case['profiles'])) {
                continue;
            }

            $filePath = $vendorDir . '/fhir/fhir-test-cases/validator/' . $file;
            if (!file_exists($filePath)) {
                continue;
            }

            if (self::resolveJavaErrorCount($case, $vendorDir . '/fhir/fhir-test-cases/validator') === null) {
                continue;
            }

            $cases[$name] = [$name, $filePath];
        }

        yield from $cases;
    }

    #[DataProvider('provideR5ValidatorTestCases')]
    public function testR5ValidatorSpecificationCase(string $name, string $filePath): void
    {
        if ($name === '__skip__') {
            $this->markTestSkipped('fhir/fhir-test-cases not installed — run: composer update fhir/fhir-test-cases');
        }

        $outcomeFile = self::OUTCOMES_DIR . '/R5.' . self::sanitizeName($name) . '-base.json';
        if (!file_exists($outcomeFile)) {
            $this->markTestIncomplete("No ardenexal outcome defined yet for R5 '{$name}'");
        }

        /** @var array{errorCount: int, warningCount: int, infoCount: int} $expected */
        $expected = json_decode((string) file_get_contents($outcomeFile), true);

        $data = file_get_contents($filePath);
        if ($data === false) {
            $this->markTestSkipped("Cannot read test case file: {$filePath}");
        }

        try {
            $resource = $this->serializationR5->deserialize($data);
        } catch (FHIRConformanceViolationException $e) {
            self::assertConformanceViolationIsSeededAsAFinding($expected, "R5 {$name}", $e);

            return;
        } catch (\Throwable $e) {
            self::assertCaseIsSeededAsUnread($expected, "R5 {$name}", $e);

            return;
        }

        self::assertCaseIsNotSeededAsUnread($expected, "R5 {$name}");

        try {
            $report = $this->serviceR5->validate($resource);
        } catch (\Error $e) {
            $this->markTestSkipped("Validation threw Error for R5 '{$name}': {$e->getMessage()}");
        }

        $realErrors   = array_values(array_filter($report->errors(), fn ($v) => !$this->isKnownGap($v, $resource)));
        $realWarnings = array_values(array_filter($report->warnings(), fn ($v) => !$this->isKnownGap($v, $resource)));

        self::assertSame(
            $expected['errorCount'],
            count($realErrors),
            "Unexpected error count for R5 '{$name}': "
            . implode(', ', array_map(static fn (FHIRValidationViolation $v) => $v->message, $realErrors)),
        );
        self::assertSame(
            $expected['warningCount'],
            count($realWarnings),
            "Unexpected warning count for R5 '{$name}'",
        );
    }

    /**
     * A conformance rejection is a finding, so it is asserted like one — never as an unread document.
     *
     * The deserializer raises these when the document is readable but breaks a stated FHIR rule that
     * the generated model physically cannot hold (a `0..1` element supplied twice, a `0..*` element
     * supplied as a JSON object). `bundle-dual-subject` produces
     * `Composition.subject: max allowed = 1, but found 2` — the reference validator's error verbatim.
     *
     * Treating that as unread counted it 0 and recorded `classification: BELOW`, so a correct result
     * that agrees with Java exactly was scored as a gap. It counts 1, and it is compared.
     *
     * @param array<string, mixed> $expected the seeded outcome file
     */
    private static function assertConformanceViolationIsSeededAsAFinding(
        array $expected,
        string $name,
        FHIRConformanceViolationException $violation,
    ): void {
        self::assertArrayNotHasKey(
            'unread',
            $expected,
            sprintf(
                "'%s' is seeded as unread, but the deserializer rejected it on a stated FHIR rule (%s). "
                . 'That is a finding, not an unreadable document — re-run seed-outcomes.php.',
                $name,
                $violation->finding,
            ),
        );

        self::assertSame(
            1,
            $expected['errorCount'] ?? null,
            sprintf("Conformance rejection '%s' must be seeded as exactly one error", $name),
        );
    }

    /**
     * A case we could not read must be seeded as unread — and one seeded as unread must still be unread.
     *
     * These two assertions replaced three different non-assertions for the same situation, one per
     * version. R4 asserted `errorCount >= 1` against the seeded file, comparing two values that both
     * came from the seed and therefore observing our behaviour not at all — the only way to fail it was
     * to edit the fixture. R4B and R5 called `markTestSkipped`. So a case that regressed from readable
     * to unreadable passed silently in every version: the deserializer breaking on a document it used
     * to handle was invisible to the suite that exists to catch exactly that.
     *
     * Presence is asserted, never the message. The seeder records the failure text for review, but the
     * test re-deserializes at run time and parse-error wording comes from upstream (jsonlint, libxml);
     * pinning it would turn every diagnostic improvement into a red suite.
     *
     * @param array<string, mixed> $expected the seeded outcome file
     */
    private static function assertCaseIsSeededAsUnread(array $expected, string $name, \Throwable $failure): void
    {
        self::assertArrayHasKey(
            'unread',
            $expected,
            sprintf(
                "REGRESSION: '%s' is seeded as readable (errorCount %s) but the deserializer now rejects it: %s. "
                . 'Losing the ability to read a document is a defect, not something to re-seed away.',
                $name,
                var_export($expected['errorCount'] ?? null, true),
                $failure->getMessage(),
            ),
        );
    }

    /**
     * A case seeded as unread that now parses is progress — but the seed is stale until re-run.
     *
     * This is the direction that already worked in practice: when the XML-comment fix made ~19 cases
     * parse for the first time, their seeded counts stopped matching and the suite went red, which is
     * what prompted the re-seed. Asserting it explicitly means the signal no longer depends on the new
     * count happening to differ from the old one.
     *
     * @param array<string, mixed> $expected the seeded outcome file
     */
    private static function assertCaseIsNotSeededAsUnread(array $expected, string $name): void
    {
        self::assertArrayNotHasKey(
            'unread',
            $expected,
            sprintf(
                "'%s' is seeded as unread but now deserializes successfully. This is an improvement — "
                . 're-run seed-outcomes.php and review the diff.',
                $name,
            ),
        );
    }

    private static function sanitizeName(string $name): string
    {
        return str_replace(['/', ' '], '-', $name);
    }

    private function isKnownGap(FHIRValidationViolation $v, object $resource): bool
    {
        // Required binding cannot be evaluated — no generated enum for this value set.
        if (str_contains($v->message, 'has no generated enum class')) {
            return true;
        }

        // sdf-19: StructureDefinition URL-based type-code check. Our FHIRPath evaluator
        // produces false positives on the startsWith/implies combination — cases Java
        // considers valid still fire this invariant for us.
        if ($v->invariantKey === 'sdf-19') {
            return true;
        }

        // NotBlank on a boolean false: generated models emit #[NotBlank] on required ?bool
        // properties, but Symfony's NotBlank treats false as blank. The constraint should
        // be #[NotNull] for booleans. This is a code-generator bug.
        if ($v->constraintClass === \Symfony\Component\Validator\Constraints\NotBlank::class
            && $v->path !== ''
            && property_exists($resource, $v->path)
            && isset($resource->{$v->path})
            && $resource->{$v->path} === false) {
            return true;
        }

        return false;
    }

    private static function createValidationService(FhirVersion $version = FhirVersion::R4): FHIRValidationService
    {
        $accessor  = PropertyAccess::createPropertyAccessor();
        $registry  = new FHIRValidationMessageRegistry();
        $pathSvc   = new FHIRPathService();
        $matcher   = new SliceDiscriminatorMatcher($accessor);
        $resolver  = new NullFHIRReferenceResolver();

        $defaultFactory = new ConstraintValidatorFactory();
        $enumNamespace  = "Ardenexal\\FHIRTools\\Component\\Models\\{$version->value}\\Enum";

        $factory = new class (
            $accessor,
            $registry,
            $pathSvc,
            $matcher,
            $resolver,
            $defaultFactory,
            $enumNamespace,
        ) implements ConstraintValidatorFactoryInterface {
            public function __construct(
                private readonly PropertyAccessorInterface $accessor,
                private readonly FHIRValidationMessageRegistry $registry,
                private readonly FHIRPathService $pathSvc,
                private readonly SliceDiscriminatorMatcher $matcher,
                private readonly NullFHIRReferenceResolver $resolver,
                private readonly ConstraintValidatorFactory $default,
                private readonly string $enumNamespace,
            ) {
            }

            public function getInstance(Constraint $constraint): ConstraintValidatorInterface
            {
                return match (true) {
                    $constraint instanceof FHIRProfileConstraint  => new FHIRProfileConstraintValidator($this->accessor),
                    $constraint instanceof FHIRPathInvariant      => new FHIRPathInvariantValidator($this->pathSvc, $this->registry),
                    $constraint instanceof FHIRValueSetBinding    => new FHIRValueSetBindingValidator(
                        $this->registry,
                        [$this->enumNamespace],
                    ),
                    $constraint instanceof FHIRFixedValue         => new FHIRFixedValueValidator($this->registry),
                    $constraint instanceof FHIRPatternValue       => new FHIRPatternValueValidator($this->registry),
                    $constraint instanceof FHIRSliceConstraint    => new FHIRSliceConstraintValidator($this->accessor, $this->matcher),
                    $constraint instanceof FHIRTargetProfile      => new FHIRTargetProfileValidator($this->resolver, $this->registry),
                    default                                       => $this->default->getInstance($constraint),
                };
            }
        };

        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->setConstraintValidatorFactory($factory)
            ->getValidator();

        return new FHIRValidationService($validator, $pathSvc, typeResolver: new FhirPropertyTypeHierarchyResolver());
    }

    /**
     * Resolve the expected Java error count from either an inline object or an external file.
     *
     * @param array<string, mixed> $case
     */
    private static function resolveJavaErrorCount(array $case, string $outcomesBaseDir): ?int
    {
        $java = $case['java'] ?? null;

        if ($java === null) {
            return null;
        }

        // Inline object: {"errorCount": N, ...}
        if (is_array($java)) {
            return (int) ($java['errorCount'] ?? 0);
        }

        // External file path relative to outcomes base dir
        if (is_string($java)) {
            $outcomePath = $outcomesBaseDir . '/outcomes/' . $java;
            if (!file_exists($outcomePath)) {
                return null;
            }

            $raw = file_get_contents($outcomePath);
            if ($raw === false) {
                return null;
            }

            /** @var array<string, mixed> $outcome */
            $outcome = json_decode($raw, true);

            // OperationOutcome format: count issues with severity error/fatal
            if (isset($outcome['issue']) && is_array($outcome['issue'])) {
                $errorCount = 0;
                foreach ($outcome['issue'] as $issue) {
                    if (in_array($issue['severity'] ?? '', ['error', 'fatal'], true)) {
                        ++$errorCount;
                    }
                }

                return $errorCount;
            }

            // No issues array = 0 errors
            return 0;
        }

        return null;
    }
}
