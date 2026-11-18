<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Profile;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Profile\ObservationBpProfile;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationOptions;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationServiceBuilder;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\Profile\VitalSignsProfiles;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\CoreStructureDefinitionPackages;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Base R5 Observations checked against the core vital-signs profile their LOINC code implies.
 *
 * The profiles come from the committed, trimmed R5 core definitions, so the findings are the ones
 * the real `bp` and `vitalsigns` StructureDefinitions produce.
 */
#[CoversClass(FHIRValidationService::class)]
#[CoversClass(FHIRValidationOptions::class)]
#[CoversClass(VitalSignsProfiles::class)]
final class VitalSignsProfilesTest extends TestCase
{
    private const string BP = 'http://hl7.org/fhir/StructureDefinition/bp';

    private const string VITALSIGNS = 'http://hl7.org/fhir/StructureDefinition/vitalsigns';

    private FHIRValidationService $service;

    private FHIRSerializationService $serializer;

    protected function setUp(): void
    {
        $fixtures = CoreStructureDefinitionPackages::parityFixtures(FhirVersion::R5);
        if ($fixtures === null) {
            self::markTestSkipped('The R5 core profile fixtures are not seeded.');
        }

        $this->service    = FHIRValidationServiceBuilder::create()->withPackageDirectories($fixtures)->build();
        $this->serializer = FHIRSerializationService::createDefault(FhirVersion::R5);
    }

    public function testBloodPressureCodeChecksTheBpProfile(): void
    {
        $groups = $this->profileGroups($this->bloodPressureWithoutSystolic(), new FHIRValidationOptions(profilesFromVitalSignsCodes: true));

        self::assertContains(self::BP, $groups);
        // The implied profile brings its ancestors, as a claimed profile does.
        self::assertContains(self::VITALSIGNS, $groups);
    }

    /**
     * `obs-temp-bad` in the HL7 test cases: a body temperature coded only in SNOMED CT, with no
     * category, so the `bodytemp` profile and its `vitalsigns` parent have something to report.
     */
    public function testTheSnomedBodyTemperatureCodeChecksTheBodytempProfile(): void
    {
        $observation = $this->serializer->deserialize((string) json_encode([
            'resourceType'      => 'Observation',
            'status'            => 'final',
            'code'              => ['coding' => [['system' => VitalSignsProfiles::SNOMED_CT, 'code' => '276885007']]],
            'subject'           => ['reference' => 'Patient/example'],
            'effectiveDateTime' => '2026-11-17',
            'valueQuantity'     => ['value' => 36.0, 'unit' => 'Cel', 'system' => 'http://unitsofmeasure.org', 'code' => 'Cel'],
        ]));

        self::assertContains(
            'http://hl7.org/fhir/StructureDefinition/bodytemp',
            $this->profileGroups($observation, new FHIRValidationOptions(profilesFromVitalSignsCodes: true)),
        );
    }

    public function testNothingIsImpliedWhenTheOptionIsOff(): void
    {
        self::assertSame([], $this->profileGroups($this->bloodPressureWithoutSystolic(), new FHIRValidationOptions()));
    }

    public function testTheSameCodeInAnotherSystemImpliesNothing(): void
    {
        $observation = $this->serializer->deserialize((string) json_encode(self::bloodPressureJson('http://example.org/codes')));

        self::assertSame([], $this->profileGroups($observation, new FHIRValidationOptions(profilesFromVitalSignsCodes: true)));
    }

    public function testAProfileMetaAlsoNamesIsCheckedOnce(): void
    {
        $json         = self::bloodPressureJson(meta: ['profile' => [self::BP . '|5.0.0']]);
        $observation  = $this->serializer->deserialize((string) json_encode($json));
        $fromMetaOnly = $this->profileViolations($observation, new FHIRValidationOptions(profilesFromMeta: true));
        $both         = $this->profileViolations($observation, new FHIRValidationOptions(profilesFromMeta: true, profilesFromVitalSignsCodes: true));

        self::assertNotSame([], $fromMetaOnly);
        self::assertEquals($fromMetaOnly, $both);
    }

    public function testARequestedVersionOfTheProfileIsNotCheckedAgain(): void
    {
        $observation = $this->bloodPressureWithoutSystolic();
        $requested   = $this->profileViolations($observation, new FHIRValidationOptions(profileUrls: [self::BP . '|5.0.0']));
        $both        = $this->profileViolations($observation, new FHIRValidationOptions(profileUrls: [self::BP . '|5.0.0'], profilesFromVitalSignsCodes: true));

        self::assertNotSame([], $requested);
        // The implied bp adds only its vitalsigns parent, which the requested URL alone does not bring.
        self::assertEquals(
            $requested,
            array_values(array_filter($both, static fn (FHIRValidationViolation $v): bool => $v->profileGroup === self::BP)),
        );
    }

    /**
     * A typed profile instance is checked from its class, with no resolver at all.
     *
     * Without the option only the base rules run on it, because no profile group was asked for.
     */
    public function testATypedProfileInstanceRunsItsClassProfiles(): void
    {
        $observation = new ObservationBpProfile(code: new CodeableConcept(coding: [
            new Coding(system: new UriPrimitive(value: VitalSignsProfiles::LOINC), code: new CodePrimitive(value: '85354-9')),
        ]));
        $service = FHIRValidationServiceBuilder::create()->build();
        $groups  = static fn (FHIRValidationOptions $options): array => array_values(array_unique(array_filter(array_map(
            static fn (FHIRValidationViolation $v): ?string => $v->profileGroup,
            $service->validateWithOptions($observation, $options)->violations,
        ))));

        self::assertSame([], $groups(new FHIRValidationOptions()));
        $implied = $groups(new FHIRValidationOptions(profilesFromVitalSignsCodes: true));
        sort($implied);
        self::assertSame([self::BP, self::VITALSIGNS], $implied);
    }

    public function testAnObservationInsideABundleIsChecked(): void
    {
        $bundle = $this->serializer->deserialize((string) json_encode([
            'resourceType' => 'Bundle',
            'type'         => 'collection',
            'entry'        => [['fullUrl' => 'urn:uuid:4f1c2a3e-8a4b-4b8e-9c55-3a2b1d0e6f71', 'resource' => self::bloodPressureJson()]],
        ]));

        $paths = array_map(
            static fn (FHIRValidationViolation $v): string => $v->path,
            array_filter(
                $this->profileViolations($bundle, new FHIRValidationOptions(profilesFromVitalSignsCodes: true)),
                static fn (FHIRValidationViolation $v): bool => $v->profileGroup === self::BP,
            ),
        );

        self::assertNotSame([], $paths);
        foreach ($paths as $path) {
            self::assertStringStartsWith('entry[0].resource', $path);
        }
    }

    public function testAnImpliedProfileThatCannotBeResolvedIsSkippedQuietly(): void
    {
        $service = FHIRValidationServiceBuilder::create()->build();
        $report  = $service->validateWithOptions($this->bloodPressureWithoutSystolic(), new FHIRValidationOptions(profilesFromVitalSignsCodes: true));

        self::assertSame([], array_values(array_filter(
            $report->violations,
            static fn (FHIRValidationViolation $v): bool => $v->code === FHIRViolationCode::PROFILE_NOT_CHECKED || $v->profileGroup !== null,
        )));
    }

    private function bloodPressureWithoutSystolic(): object
    {
        return $this->serializer->deserialize((string) json_encode(self::bloodPressureJson()));
    }

    /**
     * A blood pressure Observation holding only its diastolic component, so `bp` has something to report.
     *
     * @param array<string, mixed>|null $meta
     *
     * @return array<string, mixed>
     */
    private static function bloodPressureJson(string $system = VitalSignsProfiles::LOINC, ?array $meta = null): array
    {
        $json = [
            'resourceType'      => 'Observation',
            'status'            => 'final',
            'category'          => [['coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/observation-category', 'code' => 'vital-signs']]]],
            'code'              => ['coding' => [['system' => $system, 'code' => '85354-9']]],
            'subject'           => ['reference' => 'Patient/example'],
            'effectiveDateTime' => '2026-11-17',
            'component'         => [[
                'code'          => ['coding' => [['system' => VitalSignsProfiles::LOINC, 'code' => '8462-4']]],
                'valueQuantity' => ['value' => 80, 'unit' => 'mmHg', 'system' => 'http://unitsofmeasure.org', 'code' => 'mm[Hg]'],
            ]],
        ];

        return $meta === null ? $json : ['meta' => $meta, ...$json];
    }

    /**
     * The distinct profile groups that reported findings, sorted.
     *
     * @return list<string>
     */
    private function profileGroups(object $resource, FHIRValidationOptions $options): array
    {
        $groups = array_values(array_unique(array_map(
            static fn (FHIRValidationViolation $v): string => (string) $v->profileGroup,
            $this->profileViolations($resource, $options),
        )));
        sort($groups);

        return $groups;
    }

    /**
     * @return list<FHIRValidationViolation>
     */
    private function profileViolations(object $resource, FHIRValidationOptions $options): array
    {
        return array_values(array_filter(
            $this->service->validateWithOptions($resource, $options)->violations,
            static fn (FHIRValidationViolation $v): bool => $v->profileGroup !== null,
        ));
    }
}
