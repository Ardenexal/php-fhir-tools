<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Profile;

use Ardenexal\FHIRTools\Component\Models\R4\Profile\ObservationVitalsignsProfile;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationOptions;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\Profile\InMemoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileAttributeReader;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileResolver;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\OracleValidationServiceFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Validation of base R4 resources against profiles that exist only as StructureDefinitions.
 *
 * The definitions are written inline and trimmed to what the compiler reads, so each test names the
 * one rule it exercises. Findings are compared by profile group, path and message; the base
 * resource's own findings (group null) are not part of what is being tested.
 */
#[CoversClass(FHIRValidationService::class)]
#[CoversClass(FHIRValidationOptions::class)]
#[CoversClass(RuntimeProfileAttributeReader::class)]
final class RuntimeProfileValidationTest extends TestCase
{
    private const string CORE = 'http://hl7.org/fhir/StructureDefinition/';

    private const string EXAMPLE = 'http://example.org/fhir/StructureDefinition/';

    private const string NEED_SUBJECT = self::EXAMPLE . 'need-subject';

    private const string NEED_ISSUED = self::EXAMPLE . 'need-issued';

    private const string LAB_CATEGORY = self::EXAMPLE . 'lab-category';

    private const string LAB_ONLY = self::EXAMPLE . 'lab-only';

    private const string PARENT = self::EXAMPLE . 'parent';

    private const string CHILD_OF_V2 = self::EXAMPLE . 'child-of-parent-2';

    private const string MISSING_SUBJECT = 'This collection should contain exactly 1 element.|This collection should contain exactly 1 elements.';

    private const string MISSING_ISSUED = self::MISSING_SUBJECT;

    private FHIRValidationService $service;

    private FHIRSerializationService $serializer;

    protected function setUp(): void
    {
        $registry = new InMemoryStructureDefinitionRegistry([
            self::specialization('Observation'),
            self::specialization('Patient'),
            self::profile(self::NEED_SUBJECT, self::CORE . 'Observation', [self::element('subject', 1, '1')]),
            self::profile(self::NEED_ISSUED, self::NEED_SUBJECT, [self::element('issued', 1, '1')]),
            self::profile(self::LAB_CATEGORY, self::CORE . 'Observation', self::labCategorySlice('open')),
            self::profile(self::LAB_ONLY, self::CORE . 'Observation', self::labCategorySlice('closed')),
            // Two versions of one parent, and a child bound to the second.
            self::profile(self::PARENT, self::CORE . 'Observation', [self::element('subject', 1, '1')]),
            [...self::profile(self::PARENT, self::CORE . 'Observation', [self::element('issued', 1, '1')]), 'version' => '2.0'],
            self::profile(self::CHILD_OF_V2, self::PARENT . '|2.0', [self::element('effective[x]', 1, '1')]),
            // Same URL as the generated ObservationVitalsignsProfile, with a rule that class does not have.
            self::profile(self::CORE . 'vitalsigns', self::CORE . 'Observation', [self::element('issued', 1, '1')]),
        ]);

        $this->service    = OracleValidationServiceFactory::create(FhirVersion::R4, false, new RuntimeProfileResolver($registry));
        $this->serializer = FHIRSerializationService::createDefault(FhirVersion::R4);
    }

    public function testRequestedProfileAppliesItsRules(): void
    {
        self::assertSame(
            [[self::NEED_SUBJECT, 'subject', self::MISSING_SUBJECT]],
            $this->profileFindings($this->observation(), [self::NEED_SUBJECT]),
        );
    }

    public function testAncestorsApplyOnlyWhenDerivingProfiles(): void
    {
        self::assertSame(
            [[self::NEED_ISSUED, 'issued', self::MISSING_ISSUED]],
            $this->profileFindings($this->observation(), [self::NEED_ISSUED]),
        );
        self::assertSame(
            [[self::NEED_SUBJECT, 'subject', self::MISSING_SUBJECT], [self::NEED_ISSUED, 'issued', self::MISSING_ISSUED]],
            $this->profileFindings($this->observation(), [self::NEED_ISSUED], deriveProfilesFromClass: true),
        );
    }

    public function testConformingResourceHasNoProfileFindings(): void
    {
        $observation = $this->observation(['subject' => ['reference' => 'Patient/1'], 'issued' => '2026-01-01T00:00:00Z']);

        self::assertSame([], $this->profileFindings($observation, [self::NEED_ISSUED], deriveProfilesFromClass: true));
    }

    public function testMissingRequiredSliceIsReported(): void
    {
        self::assertSame(
            [[self::LAB_CATEGORY, 'category', 'Slice "lab" on "category" requires at least 1 item(s), but 0 matched.']],
            $this->profileFindings($this->observation(['category' => [self::category('vital-signs')]]), [self::LAB_CATEGORY]),
        );
        self::assertSame(
            [],
            $this->profileFindings($this->observation(['category' => [self::category('laboratory')]]), [self::LAB_CATEGORY]),
        );
    }

    public function testClosedSlicingRejectsItemsNoSliceMatches(): void
    {
        $findings = $this->profileFindings(
            $this->observation(['category' => [self::category('laboratory'), self::category('vital-signs')]]),
            [self::LAB_ONLY],
        );

        self::assertCount(1, $findings);
        self::assertSame([self::LAB_ONLY, 'category'], [$findings[0][0], $findings[0][1]]);
    }

    public function testUnknownProfileIsAWarning(): void
    {
        $url        = self::EXAMPLE . 'nowhere';
        $violations = $this->profileViolations($this->observation(), [$url]);

        self::assertCount(1, $violations);
        self::assertSame('warning', $violations[0]->severity);
        self::assertSame("Profile reference '{$url}' has not been checked because it is unknown", $violations[0]->message);
        self::assertSame('unknown', $violations[0]->parameters['reason'] ?? null);
    }

    public function testProfileForAnotherTypeIsAnError(): void
    {
        $patient    = $this->serializer->deserialize((string) json_encode(['resourceType' => 'Patient']));
        $violations = $this->profileViolations($patient, [self::NEED_SUBJECT]);

        self::assertCount(1, $violations);
        self::assertSame('error', $violations[0]->severity);
        self::assertSame(
            "Profile '" . self::NEED_SUBJECT . "' constrains Observation, so it cannot be used to validate a Patient",
            $violations[0]->message,
        );
    }

    public function testProfileTheClassDeclaresIsNotCheckedAgainAtRuntime(): void
    {
        $observation = new ObservationVitalsignsProfile();

        $paths = array_column($this->profileFindings($observation, [self::CORE . 'vitalsigns'], deriveProfilesFromClass: true), 1);

        self::assertNotContains('issued', $paths, 'the runtime vitalsigns definition must not run on the generated class');
        self::assertNotSame([], $paths, 'the generated vitalsigns rules still run');
    }

    public function testMetaProfilesApplyWhenAskedFor(): void
    {
        $observation = $this->observation(['meta' => ['profile' => [self::NEED_ISSUED]]]);

        self::assertSame([], $this->profileFindings($observation, []));
        self::assertSame(
            [[self::NEED_SUBJECT, 'subject', self::MISSING_SUBJECT], [self::NEED_ISSUED, 'issued', self::MISSING_ISSUED]],
            $this->profileFindings($observation, [], profilesFromMeta: true),
        );
    }

    public function testBundleEntryGetsARequestedProfileOnlyWhenItClaimsIt(): void
    {
        $bundle = $this->serializer->deserialize((string) json_encode([
            'resourceType' => 'Bundle',
            'type'         => 'collection',
            'entry'        => [
                ['fullUrl' => 'urn:uuid:1', 'resource' => self::observationJson(['meta' => ['profile' => [self::NEED_SUBJECT]]])],
                ['fullUrl' => 'urn:uuid:2', 'resource' => self::observationJson()],
            ],
        ]));

        self::assertSame(
            [[self::NEED_SUBJECT, 'entry[0].resource.subject', self::MISSING_SUBJECT]],
            $this->profileFindings($bundle, [self::NEED_SUBJECT]),
            'a profile that reaches an entry is not a type mismatch on the Bundle',
        );
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function versionSpellings(): iterable
    {
        yield 'versioned request, unversioned claim' => [self::NEED_SUBJECT . '|1.0', self::NEED_SUBJECT];
        yield 'unversioned request, versioned claim' => [self::NEED_SUBJECT, self::NEED_SUBJECT . '|1.0'];
    }

    #[DataProvider('versionSpellings')]
    public function testBundleEntryClaimIgnoresTheVersionSuffix(string $requested, string $claimed): void
    {
        $bundle = $this->serializer->deserialize((string) json_encode([
            'resourceType' => 'Bundle',
            'type'         => 'collection',
            'entry'        => [['fullUrl' => 'urn:uuid:1', 'resource' => self::observationJson(['meta' => ['profile' => [$claimed]]])]],
        ]));

        self::assertSame(
            [[self::NEED_SUBJECT, 'entry[0].resource.subject', self::MISSING_SUBJECT]],
            $this->profileFindings($bundle, [$requested]),
        );
    }

    public function testResourceInsideParametersIsCheckedAgainstItsMetaProfiles(): void
    {
        $parameters = $this->serializer->deserialize((string) json_encode([
            'resourceType' => 'Parameters',
            'parameter'    => [['name' => 'obs', 'resource' => self::observationJson(['meta' => ['profile' => [self::NEED_SUBJECT]]])]],
        ]));

        self::assertSame(
            [[self::NEED_SUBJECT, 'parameter[0].resource.subject', self::MISSING_SUBJECT]],
            $this->profileFindings($parameters, [], profilesFromMeta: true),
        );
    }

    public function testTwoVersionsOfOneProfileAreNotCheckedTogether(): void
    {
        $observation = $this->observation(['meta' => ['profile' => [self::NEED_SUBJECT . '|2.0']]]);
        $violations  = $this->profileViolations($observation, [self::NEED_SUBJECT . '|1.0'], profilesFromMeta: true);

        self::assertSame(
            [
                ['warning', '', "Profile reference '" . self::NEED_SUBJECT . "|2.0' has not been checked: '" . self::NEED_SUBJECT . "|1.0' is already checked on this resource, and two versions of one profile cannot be checked together"],
                ['error', 'subject', self::MISSING_SUBJECT],
            ],
            array_map(static fn (FHIRValidationViolation $v): array => [$v->severity, $v->path, $v->message], $violations),
        );
    }

    public function testChainNeedingAnotherVersionOfAnAncestorIsNotChecked(): void
    {
        $violations = $this->profileViolations(
            $this->observation(),
            [self::PARENT . '|1.0', self::CHILD_OF_V2],
            deriveProfilesFromClass: true,
        );

        self::assertSame(
            [
                ['warning', '', "Profile reference '" . self::CHILD_OF_V2 . "' has not been checked: it needs a different version of '" . self::PARENT . "' than another profile checked on this resource"],
                ['error', 'subject', self::MISSING_SUBJECT],
            ],
            array_map(static fn (FHIRValidationViolation $v): array => [$v->severity, $v->path, $v->message], $violations),
        );
    }

    public function testProfileForAnotherTypeThatReachesNothingIsAnError(): void
    {
        $bundle = $this->serializer->deserialize((string) json_encode([
            'resourceType' => 'Bundle',
            'type'         => 'collection',
            'entry'        => [['fullUrl' => 'urn:uuid:1', 'resource' => self::observationJson()]],
        ]));

        self::assertSame(
            [[self::NEED_SUBJECT, '', "Profile '" . self::NEED_SUBJECT . "' constrains Observation, so it cannot be used to validate a Bundle"]],
            $this->profileFindings($bundle, [self::NEED_SUBJECT]),
        );
    }

    public function testContainedResourceIsCheckedAgainstItsOwnMetaProfiles(): void
    {
        $observation = $this->observation([
            'subject'   => ['reference' => '#p'],
            'contained' => [self::observationJson(['id' => 'p', 'meta' => ['profile' => [self::NEED_SUBJECT]]])],
        ]);

        self::assertSame(
            [[self::NEED_SUBJECT, 'contained[0].subject', self::MISSING_SUBJECT]],
            $this->profileFindings($observation, [], profilesFromMeta: true),
        );
    }

    /**
     * @param array<string, mixed> $extra
     */
    private function observation(array $extra = []): object
    {
        return $this->serializer->deserialize((string) json_encode(self::observationJson($extra)));
    }

    /**
     * @param array<string, mixed> $extra
     *
     * @return array<string, mixed>
     */
    private static function observationJson(array $extra = []): array
    {
        return ['resourceType' => 'Observation', 'status' => 'final', 'code' => ['text' => 'x'], ...$extra];
    }

    /**
     * @return array<string, mixed>
     */
    private static function category(string $code): array
    {
        return ['coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/observation-category', 'code' => $code]]];
    }

    /**
     * Profile findings as [group, path, message], in the order reported.
     *
     * @param list<string> $profileUrls
     *
     * @return list<array{0: string, 1: string, 2: string}>
     */
    private function profileFindings(
        object $resource,
        array $profileUrls,
        bool $deriveProfilesFromClass = false,
        bool $profilesFromMeta = false,
    ): array {
        return array_map(
            static fn (FHIRValidationViolation $v): array => [(string) $v->profileGroup, $v->path, $v->message],
            $this->profileViolations($resource, $profileUrls, $deriveProfilesFromClass, $profilesFromMeta),
        );
    }

    /**
     * @param list<string> $profileUrls
     *
     * @return list<FHIRValidationViolation>
     */
    private function profileViolations(
        object $resource,
        array $profileUrls,
        bool $deriveProfilesFromClass = false,
        bool $profilesFromMeta = false,
    ): array {
        $report = $this->service->validateWithOptions($resource, new FHIRValidationOptions(
            profileUrls: $profileUrls,
            deriveProfilesFromClass: $deriveProfilesFromClass,
            profilesFromMeta: $profilesFromMeta,
        ));

        return array_values(array_filter(
            $report->violations,
            static fn (FHIRValidationViolation $v): bool => $v->profileGroup !== null,
        ));
    }

    /**
     * @return array<string, mixed>
     */
    private static function specialization(string $type): array
    {
        return [
            'resourceType' => 'StructureDefinition',
            'url'          => self::CORE . $type,
            'fhirVersion'  => '4.0.1',
            'kind'         => 'resource',
            'type'         => $type,
            'derivation'   => 'specialization',
            'snapshot'     => ['element' => [['id' => $type, 'path' => $type]]],
        ];
    }

    /**
     * An Observation constraint profile whose differential and snapshot both hold $elements.
     *
     * @param list<array<string, mixed>> $elements
     *
     * @return array<string, mixed>
     */
    private static function profile(string $url, string $parent, array $elements): array
    {
        $elements = [['id' => 'Observation', 'path' => 'Observation'], ...$elements];

        return [
            'resourceType'   => 'StructureDefinition',
            'url'            => $url,
            'version'        => '1.0',
            'fhirVersion'    => '4.0.1',
            'kind'           => 'resource',
            'type'           => 'Observation',
            'baseDefinition' => $parent,
            'derivation'     => 'constraint',
            'snapshot'       => ['element' => $elements],
            'differential'   => ['element' => $elements],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function element(string $path, int $min, string $max): array
    {
        return ['id' => "Observation.{$path}", 'path' => "Observation.{$path}", 'min' => $min, 'max' => $max];
    }

    /**
     * Observation.category sliced by coding.code, with one required slice "lab" for "laboratory".
     *
     * @return list<array<string, mixed>>
     */
    private static function labCategorySlice(string $rules): array
    {
        return [
            [
                'id'      => 'Observation.category',
                'path'    => 'Observation.category',
                'slicing' => ['discriminator' => [['type' => 'value', 'path' => 'coding.code']], 'rules' => $rules],
                'min'     => 1,
                'max'     => '*',
            ],
            ['id' => 'Observation.category:lab', 'path' => 'Observation.category', 'sliceName' => 'lab', 'min' => 1, 'max' => '1'],
            ['id' => 'Observation.category:lab.coding', 'path' => 'Observation.category.coding', 'min' => 1, 'max' => '*'],
            [
                'id'        => 'Observation.category:lab.coding.code',
                'path'      => 'Observation.category.coding.code',
                'min'       => 1,
                'max'       => '1',
                'fixedCode' => 'laboratory',
            ],
        ];
    }
}
