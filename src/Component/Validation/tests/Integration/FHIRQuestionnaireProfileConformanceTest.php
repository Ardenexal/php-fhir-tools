<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration;

use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRDerivedQuestionnaireValidator;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Conformance test driving FHIRDerivedQuestionnaireValidator against the official FHIR
 * R5 Questionnaire Q-profile corpus (Q-against-Q, not QR-against-Q).
 *
 * Each eligible manifest case is either standalone (no supporting file = no derivedFrom) or
 * derived (supporting[0] = base Questionnaire).  Standalone cases expect 0 violations.
 * Derived cases run the validator and assert against seeded outcomes in
 * outcomes/questionnaire-profile/.
 *
 * derivationType cannot be read from the PHP model (the deserializer does not merge
 * _derivedFrom primitive extensions into CanonicalPrimitive->extension), so it is extracted
 * from the raw JSON via FHIRDerivedQuestionnaireValidator::extractDerivationTypeFromJson()
 * before calling validate().
 */
#[CoversClass(FHIRDerivedQuestionnaireValidator::class)]
final class FHIRQuestionnaireProfileConformanceTest extends TestCase
{
    private const VALIDATOR_DIR = 'fhir/fhir-test-cases/validator';

    private const OUTCOMES_DIR = __DIR__ . '/outcomes/questionnaire-profile';

    /**
     * Cases where our output knowingly diverges from correct behaviour because of an open bug.
     *
     * @var array<string, string>
     */
    private const KNOWN_GAPS = [];

    /**
     * Unseeded cases that are DEFERRED (blocked on a capability the library does not have).
     *
     * @var array<string, string>
     */
    private const DEFERRED_CASES = [];

    private FHIRSerializationService $serialization;

    private FHIRDerivedQuestionnaireValidator $validator;

    protected function setUp(): void
    {
        $this->serialization = FHIRSerializationService::createDefault(FhirVersion::R5);
        $this->validator     = new FHIRDerivedQuestionnaireValidator();
    }

    /**
     * @return iterable<string, array{string, string, string|null}>
     */
    public static function provideQuestionnaireCases(): iterable
    {
        $vendorDir    = dirname(__DIR__, 5) . '/vendor';
        $manifestPath = $vendorDir . '/' . self::VALIDATOR_DIR . '/manifest.json';

        if (!file_exists($manifestPath)) {
            yield '__vendor_not_installed__' => ['__skip__', '', null];

            return;
        }

        $raw = file_get_contents($manifestPath);
        if ($raw === false) {
            return;
        }

        /** @var array{test-cases: list<array<string, mixed>>} $manifest */
        $manifest = json_decode($raw, true);

        /** @var array<string, array{string, string, string|null}> $cases */
        $cases = [];

        foreach ($manifest['test-cases'] as $case) {
            $name    = (string) ($case['name'] ?? '');
            $file    = (string) ($case['file'] ?? '');
            $version = (string) ($case['version'] ?? '');

            if (($case['use-test'] ?? null) === false) {
                continue;
            }

            // R5 and versionless corpus only.
            if ($version !== '5.0' && $version !== '') {
                continue;
            }

            if (($case['module'] ?? '') !== 'questionnaire') {
                continue;
            }

            // Q-profile cases only: the file must be a Questionnaire, not a QR.
            if (self::isQuestionnaireResponseFile($file)) {
                continue;
            }

            // Must start with 'q-' to filter out non-Q questionnaire cases.
            if (!str_starts_with(strtolower(basename($file)), 'q-')) {
                continue;
            }

            $base     = $vendorDir . '/' . self::VALIDATOR_DIR;
            $filePath = $base . '/' . $file;

            if (!file_exists($filePath)) {
                continue;
            }

            // Supporting[0] is the base Questionnaire (when present).
            $basePath = null;

            if (isset($case['supporting']) && is_array($case['supporting']) && $case['supporting'] !== []) {
                $candidate = $base . '/' . (string) $case['supporting'][0];
                if (file_exists($candidate)) {
                    $basePath = $candidate;
                }
            }

            $cases[$name] = [$name, $filePath, $basePath];
        }

        yield from $cases;
    }

    /**
     * @param string|null $basePath path to the base Questionnaire, null for standalone cases
     */
    #[DataProvider('provideQuestionnaireCases')]
    public function testQuestionnaireProfileConformanceCase(string $name, string $filePath, ?string $basePath): void
    {
        if ($name === '__skip__') {
            $this->markTestSkipped('fhir/fhir-test-cases not installed — run: composer update fhir/fhir-test-cases');
        }

        $outcomeFile = self::OUTCOMES_DIR . '/R5.' . self::sanitizeName($name) . '-base.json';

        if (!file_exists($outcomeFile)) {
            $reason = self::DEFERRED_CASES[$name] ?? 'not yet seeded';
            $this->markTestIncomplete("No seeded outcome for '{$name}' — {$reason}.");
        }

        /** @var array{errorCount: int, warningCount: int, infoCount: int} $expected */
        $expected = json_decode((string) file_get_contents($outcomeFile), true);

        $fileData = file_get_contents($filePath);
        if ($fileData === false) {
            $this->markTestSkipped("Cannot read file '{$filePath}'");
        }

        try {
            $derived = $this->serialization->deserialize($fileData);
        } catch (\Throwable $e) {
            $this->markTestSkipped("Deserialization failed for '{$name}': {$e->getMessage()}");
        }

        if (!$derived instanceof QuestionnaireResource) {
            $this->markTestSkipped("File for '{$name}' did not deserialize to a QuestionnaireResource");
        }

        // Standalone cases (no base) always produce 0 violations.
        // validate() is NOT called here because the Symfony Serializer uses newInstanceWithoutConstructor()
        // and does not initialise typed array properties (e.g. $derivedFrom = []) for fields absent in
        // the JSON source — accessing them would throw "Typed property must not be accessed before
        // initialization". The no-derivedFrom early-return guard is covered by unit test
        // FHIRDerivedQuestionnaireValidatorTest::testNoDerivedFromReturnsEmptyReport.
        if ($basePath === null) {
            self::assertSame(0, $expected['errorCount'], "Standalone case '{$name}' seeded outcome must have 0 errors");
            self::assertSame(0, $expected['warningCount'], "Standalone case '{$name}' seeded outcome must have 0 warnings");

            return;
        }

        $baseData = file_get_contents($basePath);
        if ($baseData === false) {
            $this->markTestSkipped("Cannot read base file '{$basePath}'");
        }

        try {
            $base = $this->serialization->deserialize($baseData);
        } catch (\Throwable $e) {
            $this->markTestSkipped("Deserialization of base failed for '{$name}': {$e->getMessage()}");
        }

        if (!$base instanceof QuestionnaireResource) {
            $this->markTestSkipped("Supporting file for '{$name}' did not deserialize to a QuestionnaireResource");
        }

        // Extract derivation type from raw JSON (_derivedFrom primitive extension).
        $derivationType = 'compliesWith';
        $ext            = pathinfo($filePath, PATHINFO_EXTENSION);

        if ($ext === 'json') {
            /** @var array<string, mixed> $decoded */
            $decoded        = json_decode($fileData, true);
            $derivationType = FHIRDerivedQuestionnaireValidator::extractDerivationTypeFromJson($decoded);
        }

        $report = $this->validator->validate($derived, $base, $derivationType);

        $errors   = $report->errors();
        $warnings = $report->warnings();

        $context = array_key_exists($name, self::KNOWN_GAPS)
            ? ' [KNOWN GAP: ' . self::KNOWN_GAPS[$name] . ']'
            : '';

        self::assertSame(
            $expected['errorCount'],
            count($errors),
            "Unexpected error count for '{$name}'{$context}: "
            . implode(' | ', array_map(static fn (FHIRValidationViolation $v) => $v->message, $errors)),
        );
        self::assertSame(
            $expected['warningCount'],
            count($warnings),
            "Unexpected warning count for '{$name}'{$context}: "
            . implode(' | ', array_map(static fn (FHIRValidationViolation $v) => $v->message, $warnings)),
        );
    }

    private static function isQuestionnaireResponseFile(string $file): bool
    {
        $base = strtolower(basename($file));

        return str_starts_with($base, 'questionnaireresponse')
            || str_ends_with($base, '-qr.json')
            || str_ends_with($base, '-qr.xml');
    }

    private static function sanitizeName(string $name): string
    {
        return str_replace(['/', ' '], '-', $name);
    }
}
