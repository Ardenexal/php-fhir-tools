<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BundleTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Profile\ObservationbpProfile;
use Ardenexal\FHIRTools\Component\Models\R5\Profile\ObservationvitalsignsProfile;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Bundle\BundleEntry;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\BundleResource;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ObservationResource;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationReport;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\Profile\PackageDirectoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileAttributeReader;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileResolver;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\CoreStructureDefinitionPackages;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\OracleValidationServiceFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * The hard rule of runtime profiles: a StructureDefinition validated at runtime reports exactly what
 * the profile class generated from it reports.
 *
 * Every generated profile class of a release is validated against its own URL, and a base instance
 * of the same resource is validated against the same URL with the StructureDefinition resolved at
 * runtime. Both instances get the same objects built under the profile's rule paths first
 * ({@see ProfileRulePathMaterialiser}), so the profile's rules have something to fail on. The two
 * reports must match finding for finding, with and without `deriveProfilesFromClass`.
 *
 * StructureDefinitions come from committed fixtures: each release's generated profiles and their
 * ancestors, trimmed from the core package by `tests/Fixtures/Profile/seed-core-profiles.php`. A
 * release without fixtures is skipped with the command that seeds it. Re-seed after regenerating
 * models, so a new profile class has its definition.
 */
#[CoversClass(FHIRValidationService::class)]
#[CoversClass(RuntimeProfileResolver::class)]
#[CoversClass(RuntimeProfileAttributeReader::class)]
final class RuntimeProfileParityTest extends TestCase
{
    private const string ROOT = __DIR__ . '/../../../../../..';

    private const string BP = 'http://hl7.org/fhir/StructureDefinition/bp';

    /**
     * Core example profiles published without a snapshot. The generator works from their
     * differential; runtime validation requires a snapshot, so it reports that instead.
     */
    private const array WITHOUT_SNAPSHOT = [
        'http://hl7.org/fhir/StructureDefinition/example-section-library',
        'http://hl7.org/fhir/StructureDefinition/example-composition',
    ];

    /**
     * Profiles whose rules a fixture of empty objects cannot break, so both sides reporting nothing
     * is expected. Anything not listed must produce at least one profile finding, which keeps the
     * comparison from passing on two empty reports.
     */
    private const array NO_FINDING_EXPECTED = [
        // Data-type profiles: the runtime pass covers resources until data-type profiles land.
        'MoneyQuantityProfile'                                    => 'data type',
        'SimpleQuantityProfile'                                   => 'data type',
        'DataElementConstraintOnElementDefinitionDataTypeProfile' => 'data type',
        // Their rules are extensions, maximums or fixed values, which an empty object satisfies.
        'ClinicalDocumentProfile'                       => 'extension and maximum rules only',
        'ELMLibraryProfile'                             => 'fixed values on absent elements',
        'FamilyMemberHistoryForGeneticsAnalysisProfile' => 'extension rules only',
        'DiagnosticReportGeneticsProfile'               => 'extension rules only',
        'ObservationGeneticsProfile'                    => 'extension rules only',
        'ProfileForHLAGenotypingResultsProfile'         => 'extension rules only',
        'ServiceRequestGeneticsProfile'                 => 'extension rules only',
        'CQFquestionnaireProfile'                       => 'an optional extension slice only (R4B)',
        // They mark elements must-support and state no rules (R4).
        'EHRSFMrecordLifecycleEventAuditEventProfile'  => 'must-support markers only',
        'EHRSFMrecordLifecycleEventProvenanceProfile'  => 'must-support markers only',
    ];

    /** @var array<string, array{generated: FHIRValidationService, runtime: FHIRValidationService, resolver: RuntimeProfileResolver}> */
    private static array $services = [];

    /**
     * @return iterable<string, array{FhirVersion, class-string, bool}>
     */
    public static function profileClasses(): iterable
    {
        foreach (FhirVersion::cases() as $version) {
            $files = glob(self::ROOT . "/src/Component/Models/src/{$version->value}/Profile/*Profile.php");
            foreach ($files === false ? [] : $files as $file) {
                /** @var class-string $class */
                $class = "Ardenexal\\FHIRTools\\Component\\Models\\{$version->value}\\Profile\\" . basename($file, '.php');
                foreach ([false, true] as $derive) {
                    yield sprintf('%s %s%s', $version->value, basename($file, '.php'), $derive ? ' derived' : '') => [$version, $class, $derive];
                }
            }
        }
    }

    public static function tearDownAfterClass(): void
    {
        self::$services = [];
    }

    /**
     * @param class-string $profileClass
     */
    #[DataProvider('profileClasses')]
    public function testRuntimeProfileReportsWhatTheGeneratedClassReports(FhirVersion $version, string $profileClass, bool $derive): void
    {
        $services  = self::services($version);
        $url       = self::profileUrl($profileClass);
        $baseClass = self::baseClassOf($profileClass);

        $resolution   = $services['resolver']->resolve($url, $version->value);
        $paths        = $resolution->isResolved() ? ProfileRulePathMaterialiser::rulePaths($resolution->chain) : [];
        $materialiser = new ProfileRulePathMaterialiser();

        $generatedInstance = new $profileClass();
        $baseInstance      = new $baseClass();
        $materialiser->materialise($generatedInstance, $paths);
        $materialiser->materialise($baseInstance, $paths);

        $generated = self::findings($services['generated']->validate($generatedInstance, [$url], deriveProfilesFromClass: $derive));
        $runtime   = self::findings($services['runtime']->validate($baseInstance, [$url], deriveProfilesFromClass: $derive));

        if (in_array($url, self::WITHOUT_SNAPSHOT, true)) {
            $noSnapshot = array_values(array_diff($runtime, $generated));
            self::assertCount(1, $noSnapshot, 'only the missing snapshot warning differs');
            self::assertStringContainsString('has no snapshot', $noSnapshot[0]);
            self::assertSame([], array_values(array_diff($generated, $runtime)));

            return;
        }

        self::assertSame($generated, $runtime, sprintf('%s validated at runtime differs from %s', $url, $profileClass));

        $short = substr($profileClass, (int) strrpos($profileClass, '\\') + 1);
        if (!isset(self::NO_FINDING_EXPECTED[$short])) {
            self::assertNotSame(
                [],
                array_filter($generated, static fn (string $finding): bool => str_contains($finding, " | {$url} | ")),
                "{$short} produced no finding in its own profile group, so the comparison proves nothing",
            );
        }
    }

    /**
     * A generated vital signs instance checked against blood pressure, a runtime child of its class.
     *
     * The class declares vital signs, so those rules stay generated; only blood pressure's own rules
     * come from the StructureDefinition. Together they must equal the generated blood pressure class.
     */
    #[DataProvider('deriveFlags')]
    public function testGeneratedParentWithRuntimeChildMatchesTheGeneratedChild(bool $derive): void
    {
        $services = self::services(FhirVersion::R5);
        $paths    = self::rulePathsOf($services['resolver'], self::BP);

        $generated = new ObservationbpProfile();
        $mixed     = new ObservationvitalsignsProfile();
        (new ProfileRulePathMaterialiser())->materialise($generated, $paths);
        (new ProfileRulePathMaterialiser())->materialise($mixed, $paths);

        $expected = self::findings($services['generated']->validate($generated, [self::BP], deriveProfilesFromClass: $derive));

        self::assertNotSame([], array_filter($expected, static fn (string $f): bool => str_contains($f, ' | ' . self::BP . ' | ')));
        self::assertSame($expected, self::findings($services['runtime']->validate($mixed, [self::BP], deriveProfilesFromClass: $derive)));
    }

    /**
     * A Bundle entry claiming blood pressure gets the same findings, at the same paths, either way.
     */
    #[DataProvider('deriveFlags')]
    public function testBundleEntryClaimingTheProfileMatchesTheGeneratedEntry(bool $derive): void
    {
        $services = self::services(FhirVersion::R5);
        $paths    = self::rulePathsOf($services['resolver'], self::BP);

        $generated = self::bundleOf(new ObservationbpProfile(), $paths);
        $runtime   = self::bundleOf(new ObservationResource(), $paths);

        $expected = self::findings($services['generated']->validate($generated, [self::BP], deriveProfilesFromClass: $derive));

        self::assertNotSame([], array_filter($expected, static fn (string $f): bool => str_contains($f, 'entry[0].resource.')));
        self::assertSame($expected, self::findings($services['runtime']->validate($runtime, [self::BP], deriveProfilesFromClass: $derive)));
    }

    /**
     * Cholesterol types `referenceRange.high` as SimpleQuantity, so SimpleQuantity's rule (no
     * comparator) is inlined at `referenceRange.high.comparator` in cholesterol's group, by the
     * generator and at runtime alike. A second reference range breaks it, so the inlined rule must
     * fire through the repeating element, the same way on both sides.
     */
    #[DataProvider('releases')]
    public function testInlinedDataTypeProfileMatchesTheGeneratedClass(FhirVersion $version): void
    {
        $services = self::services($version);
        $url      = 'http://hl7.org/fhir/StructureDefinition/cholesterol';
        $models   = "Ardenexal\\FHIRTools\\Component\\Models\\{$version->value}";

        $build = static function(object $observation) use ($models): object {
            $ranges = [];
            foreach ([null, '<'] as $comparator) {
                /** @var class-string $rangeClass */
                $rangeClass = "{$models}\\Resource\\Observation\\ObservationReferenceRange";
                /** @var class-string $quantityClass */
                $quantityClass = "{$models}\\DataType\\Quantity";
                /** @var class-string $comparatorClass */
                $comparatorClass = "{$models}\\DataType\\QuantityComparatorType";

                $range       = new $rangeClass();
                $range->high = new $quantityClass();
                if ($comparator !== null) {
                    $range->high->comparator = new $comparatorClass($comparator);
                }
                $ranges[] = $range;
            }
            $observation->referenceRange = $ranges;

            return $observation;
        };

        /** @var class-string $generatedClass */
        $generatedClass = "{$models}\\Profile\\CholesterolProfile";
        /** @var class-string $baseClass */
        $baseClass = "{$models}\\Resource\\ObservationResource";

        $generated = self::findings($services['generated']->validate($build(new $generatedClass()), [$url]));
        $runtime   = self::findings($services['runtime']->validate($build(new $baseClass()), [$url]));

        self::assertNotSame(
            [],
            array_filter($generated, static fn (string $f): bool => str_contains($f, ' | referenceRange.high.comparator | ') && str_contains($f, " | {$url} | ")),
            'the inlined SimpleQuantity rule reports in the cholesterol group',
        );
        self::assertSame($generated, $runtime);
    }

    /**
     * A vital signs document that breaks vs-1 (a month-precise date) and vs-3 (a component with
     * neither value nor data absent reason), and carries codes for the profile's bindings to check.
     * The generated class and the runtime definition must report the same invariant and binding
     * findings: the same keys, paths, severities and codes.
     */
    #[DataProvider('releases')]
    public function testInvariantsAndBindingsMatchTheGeneratedClass(FhirVersion $version): void
    {
        $services = self::services($version);
        $url      = 'http://hl7.org/fhir/StructureDefinition/vitalsigns';
        $models   = "Ardenexal\\FHIRTools\\Component\\Models\\{$version->value}";
        $json     = (string) json_encode([
            'resourceType'      => 'Observation',
            'status'            => 'final',
            'code'              => ['coding' => [['system' => 'http://loinc.org', 'code' => '85354-9']]],
            'effectiveDateTime' => '2024-01',
            'component'         => [
                ['code' => ['coding' => [['system' => 'http://loinc.org', 'code' => '8480-6']]]],
                ['code' => ['text' => 'diastolic'], 'valueQuantity' => ['value' => 1, 'system' => 'http://unitsofmeasure.org', 'code' => 'furlong']],
            ],
        ]);

        /** @var class-string $baseClass */
        $baseClass = "{$models}\\Resource\\ObservationResource";
        $runtime   = FHIRSerializationService::createDefault($version)->deserializeFromJson($json, $baseClass);

        // A profile class cannot be deserialized into, so it takes the base instance's elements
        $generatedClass = self::profileClassOf($version, $url);
        $generated      = new $generatedClass();
        foreach (get_object_vars($runtime) as $property => $value) {
            $generated->{$property} = $value;
        }

        $expected = self::findings($services['generated']->validate($generated, [$url]));

        $keys = array_map(static fn (string $f): string => substr($f, (int) strrpos($f, ' | ') + 3), $expected);
        self::assertContains('vs-1', $keys);
        self::assertContains('vs-3', $keys);
        self::assertNotSame([], array_filter($expected, static fn (string $f): bool => str_contains($f, ' | ' . FHIRProfileBinding::class . ' | ')));
        self::assertSame($expected, self::findings($services['runtime']->validate($runtime, [$url])));
    }

    /**
     * The generated profile class of a release that declares $url.
     *
     * @return class-string
     */
    private static function profileClassOf(FhirVersion $version, string $url): string
    {
        foreach (self::profileClasses() as [$release, $class]) {
            if ($release === $version && self::profileUrl($class) === $url) {
                return $class;
            }
        }

        self::fail("No {$version->value} profile class declares {$url}");
    }

    /**
     * @return iterable<string, array{FhirVersion}>
     */
    public static function releases(): iterable
    {
        foreach (FhirVersion::cases() as $version) {
            yield $version->value => [$version];
        }
    }

    /**
     * @return iterable<string, array{bool}>
     */
    public static function deriveFlags(): iterable
    {
        yield 'requested profile only' => [false];
        yield 'with ancestors' => [true];
    }

    /**
     * @return list<string>
     */
    private static function rulePathsOf(RuntimeProfileResolver $resolver, string $url): array
    {
        $resolution = $resolver->resolve($url, FhirVersion::R5->value);
        self::assertTrue($resolution->isResolved(), (string) $resolution->message);

        return ProfileRulePathMaterialiser::rulePaths($resolution->chain);
    }

    /**
     * @param list<string> $paths
     */
    private static function bundleOf(ObservationResource $observation, array $paths): BundleResource
    {
        (new ProfileRulePathMaterialiser())->materialise($observation, $paths);
        $observation->meta = new Meta(profile: [new CanonicalPrimitive(value: self::BP)]);

        return new BundleResource(
            type: new BundleTypeType('collection'),
            entry: [new BundleEntry(fullUrl: new UriPrimitive(value: 'urn:uuid:1'), resource: $observation)],
        );
    }

    /**
     * @return array{generated: FHIRValidationService, runtime: FHIRValidationService, resolver: RuntimeProfileResolver}
     */
    private static function services(FhirVersion $version): array
    {
        if (isset(self::$services[$version->value])) {
            return self::$services[$version->value];
        }

        $directory = CoreStructureDefinitionPackages::parityFixtures($version);
        if ($directory === null) {
            self::markTestSkipped(sprintf(
                'No %s parity fixtures. Seed them from the core package: php src/Component/Validation/tests/Fixtures/Profile/seed-core-profiles.php %s <package-dir>',
                $version->value,
                $version->value,
            ));
        }

        $runtimeResolver = new RuntimeProfileResolver(new PackageDirectoryStructureDefinitionRegistry($directory, $version->value));

        return self::$services[$version->value] = [
            'generated' => OracleValidationServiceFactory::create($version),
            'runtime'   => OracleValidationServiceFactory::create($version, true, $runtimeResolver),
            // A second resolver for reading rule paths, so the fixture never resolves a profile
            // in the runtime service's resolver before the service itself does.
            'resolver' => new RuntimeProfileResolver(new PackageDirectoryStructureDefinitionRegistry($directory, $version->value)),
        ];
    }

    /**
     * @param class-string $profileClass
     */
    private static function profileUrl(string $profileClass): string
    {
        $attributes = (new \ReflectionClass($profileClass))->getAttributes(FHIRProfile::class);
        self::assertNotSame([], $attributes, "{$profileClass} declares no FHIRProfile");

        return $attributes[0]->newInstance()->profileUrl;
    }

    /**
     * The base resource or data type class a generated profile class extends.
     *
     * @param class-string $profileClass
     *
     * @return class-string
     */
    private static function baseClassOf(string $profileClass): string
    {
        $class = get_parent_class($profileClass);
        while ($class !== false && str_contains($class, '\\Profile\\')) {
            $class = get_parent_class($class);
        }
        self::assertNotFalse($class, "{$profileClass} extends no base class");

        return $class;
    }

    /**
     * A report's findings as sorted strings, so two reports compare in one assertion.
     *
     * @return list<string>
     */
    private static function findings(FHIRValidationReport $report): array
    {
        $findings = array_map(
            static fn (FHIRValidationViolation $v): string => implode(' | ', [
                $v->severity,
                $v->path,
                $v->code,
                $v->message,
                $v->constraintClass,
                (string) $v->profileGroup,
                (string) $v->invariantKey,
            ]),
            $report->violations,
        );
        sort($findings);

        return $findings;
    }
}
