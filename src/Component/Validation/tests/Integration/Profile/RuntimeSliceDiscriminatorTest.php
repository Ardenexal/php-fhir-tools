<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRTerminologyClientInterface;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationServiceBuilder;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\InMemoryFHIRTerminologyClient;
use Ardenexal\FHIRTools\Component\Validation\Profile\InMemoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileConformance;
use Ardenexal\FHIRTools\Component\Validation\SliceDiscriminatorMatcher;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRSliceConstraintValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Slices discriminated on `$this`, on several discriminators, by a required binding, by type and by
 * profile are matched, so their cardinality and closed slicing are enforced.
 */
#[CoversClass(FHIRSliceConstraintValidator::class)]
#[CoversClass(SliceDiscriminatorMatcher::class)]
#[CoversClass(RuntimeProfileConformance::class)]
final class RuntimeSliceDiscriminatorTest extends TestCase
{
    private const string CORE = 'http://hl7.org/fhir/StructureDefinition/';

    private const string BASE = 'http://example.org/fhir/StructureDefinition/';

    private const string CATEGORY_SYSTEM = 'http://terminology.hl7.org/CodeSystem/observation-category';

    private const string PBS = 'http://example.org/ValueSet/pbs';

    private const string PBS_SYSTEM = 'http://pbs.gov.au/code/item';

    private FHIRSerializationService $serializer;

    protected function setUp(): void
    {
        $this->serializer = FHIRSerializationService::createDefault(FhirVersion::R4);
    }

    /**
     * Modelled on AU Core smoking status: `category` is sliced on `$this`, and the slice's own
     * pattern says which category belongs to it.
     */
    public function testAThisSliceIsMatchedByItsOwnPattern(): void
    {
        $profile       = self::BASE . 'smoking';
        $socialHistory = ['coding' => [['system' => self::CATEGORY_SYSTEM, 'code' => 'social-history']]];
        $registry      = self::registry(self::profile($profile, 'Observation', [
            ['id' => 'Observation.category', 'path' => 'Observation.category', 'slicing' => ['discriminator' => [['type' => 'pattern', 'path' => '$this']], 'rules' => 'open']],
            ['id' => 'Observation.category:socialHistory', 'path' => 'Observation.category', 'sliceName' => 'socialHistory', 'min' => 1, 'max' => '1', 'patternCodeableConcept' => $socialHistory],
        ]));

        self::assertSame([], $this->findings($registry, $profile, self::observation(['category' => [$socialHistory]])));
        self::assertSame(
            [['category', 'Slice "socialHistory" on "category" requires at least 1 item(s), but 0 matched.']],
            $this->findings($registry, $profile, self::observation(['category' => [['coding' => [['system' => self::CATEGORY_SYSTEM, 'code' => 'exam']]]]])),
        );
    }

    /**
     * Every discriminator must match, so a LOINC code under another system is not the LOINC slice,
     * and closed slicing rejects it.
     */
    public function testACompositeSliceNeedsEveryDiscriminator(): void
    {
        $profile  = self::BASE . 'loinc-only';
        $registry = self::registry(self::profile($profile, 'Observation', [
            ['id' => 'Observation.code.coding', 'path' => 'Observation.code.coding', 'slicing' => [
                'discriminator' => [['type' => 'value', 'path' => 'system'], ['type' => 'value', 'path' => 'code']],
                'rules'         => 'closed',
            ]],
            ['id' => 'Observation.code.coding:systolic', 'path' => 'Observation.code.coding', 'sliceName' => 'systolic', 'min' => 1, 'max' => '1'],
            ['id' => 'Observation.code.coding:systolic.system', 'path' => 'Observation.code.coding.system', 'fixedUri' => 'http://loinc.org'],
            ['id' => 'Observation.code.coding:systolic.code', 'path' => 'Observation.code.coding.code', 'fixedCode' => '8480-6'],
        ]));

        self::assertSame([], $this->findings($registry, $profile, self::observation(['code' => ['coding' => [['system' => 'http://loinc.org', 'code' => '8480-6']]]])));
        self::assertSame(
            [
                ['code.coding', 'Property "code.coding" uses closed slicing but contains 1 item(s) matching no defined slice.'],
                ['code.coding', 'Slice "systolic" on "code.coding" requires at least 1 item(s), but 0 matched.'],
            ],
            $this->findings($registry, $profile, self::observation(['code' => ['coding' => [['system' => 'http://example.org/local', 'code' => '8480-6']]]])),
        );
    }

    /**
     * Modelled on AU Core's `coding:pbs`: the slice has only a required binding, so the terminology
     * client says which codings belong to it.
     */
    public function testABindingSliceIsMatchedThroughTheTerminologyClient(): void
    {
        $profile  = self::BASE . 'pbs-medication';
        $registry = self::registry(self::profile($profile, 'Medication', [
            ['id' => 'Medication.code.coding', 'path' => 'Medication.code.coding', 'slicing' => ['discriminator' => [['type' => 'pattern', 'path' => '$this']], 'rules' => 'closed']],
            ['id' => 'Medication.code.coding:pbs', 'path' => 'Medication.code.coding', 'sliceName' => 'pbs', 'min' => 1, 'binding' => ['strength' => 'required', 'valueSet' => self::PBS]],
        ]));
        $client = new InMemoryFHIRTerminologyClient([self::PBS => [self::PBS_SYSTEM . '|1234X' => true]], defaultResult: false);

        self::assertSame([], $this->findings($registry, $profile, self::medication('1234X'), $client));

        $outside = $this->findings($registry, $profile, self::medication('9999Z'), $client);
        self::assertContains(['code.coding', 'Property "code.coding" uses closed slicing but contains 1 item(s) matching no defined slice.'], $outside);
        self::assertContains(['code.coding', 'Slice "pbs" on "code.coding" requires at least 1 item(s), but 0 matched.'], $outside);

        // Without a client nobody can say which codings are PBS ones, so neither rule is checked
        self::assertSame([], $this->findings($registry, $profile, self::medication('9999Z')));
        self::assertSame([], $this->findings($registry, $profile, ['resourceType' => 'Medication', 'code' => ['text' => 'none']]));
    }

    /**
     * A document Bundle that slices `entry` by resource type reports a missing and an extra entry.
     */
    public function testATypeSliceIsMatchedByTheResourcesType(): void
    {
        $profile  = self::BASE . 'patient-bundle';
        $registry = self::registry(self::profile($profile, 'Bundle', [
            ['id' => 'Bundle.entry', 'path' => 'Bundle.entry', 'slicing' => ['discriminator' => [['type' => 'type', 'path' => 'resource']], 'rules' => 'closed']],
            ['id' => 'Bundle.entry:patient', 'path' => 'Bundle.entry', 'sliceName' => 'patient', 'min' => 1, 'max' => '1'],
            ['id' => 'Bundle.entry:patient.resource', 'path' => 'Bundle.entry.resource', 'type' => [['code' => 'Patient']]],
        ]));

        self::assertSame([], $this->findings($registry, $profile, self::bundle(['resourceType' => 'Patient'])));
        self::assertSame(
            [
                ['entry', 'Property "entry" uses closed slicing but contains 1 item(s) matching no defined slice.'],
                ['entry', 'Slice "patient" on "entry" requires at least 1 item(s), but 0 matched.'],
            ],
            $this->findings($registry, $profile, self::bundle(['resourceType' => 'Medication', 'code' => ['text' => 'x']])),
        );
    }

    /**
     * A Bundle slicing `entry` by profile takes an entry into the slice only when it conforms to the
     * profile, checked on the side: the profile's own findings are not reported.
     */
    public function testAProfileSliceIsMatchedWhenTheEntryConforms(): void
    {
        $profile  = self::BASE . 'gendered-bundle';
        $gendered = self::BASE . 'gendered-patient';
        $registry = self::registry(
            self::profile($profile, 'Bundle', [
                ['id' => 'Bundle.entry', 'path' => 'Bundle.entry', 'slicing' => ['discriminator' => [['type' => 'profile', 'path' => 'resource']], 'rules' => 'closed']],
                ['id' => 'Bundle.entry:patient', 'path' => 'Bundle.entry', 'sliceName' => 'patient', 'min' => 1, 'max' => '1'],
                ['id' => 'Bundle.entry:patient.resource', 'path' => 'Bundle.entry.resource', 'type' => [['code' => 'Patient', 'profile' => [$gendered]]]],
            ]),
            self::profile($gendered, 'Patient', [['id' => 'Patient.gender', 'path' => 'Patient.gender', 'min' => 1]]),
        );

        self::assertSame([], $this->findings($registry, $profile, self::bundle(['resourceType' => 'Patient', 'gender' => 'female'])));
        self::assertSame(
            [
                ['entry', 'Property "entry" uses closed slicing but contains 1 item(s) matching no defined slice.'],
                ['entry', 'Slice "patient" on "entry" requires at least 1 item(s), but 0 matched.'],
            ],
            $this->findings($registry, $profile, self::bundle(['resourceType' => 'Patient'])),
        );
    }

    /**
     * A slice profile that slices an element of its own is checked through the same slice
     * validator; the outer slicing's findings must still be reported afterwards.
     */
    public function testAProfileSliceWhoseProfileSlicesKeepsItsFindings(): void
    {
        $profile  = self::BASE . 'one-mrn-patient-bundle';
        $mrn      = self::BASE . 'mrn-patient';
        $patient  = ['resourceType' => 'Patient', 'identifier' => [['system' => 'urn:mrn', 'value' => '1']]];
        $registry = self::registry(
            self::profile($profile, 'Bundle', [
                ['id' => 'Bundle.entry', 'path' => 'Bundle.entry', 'slicing' => ['discriminator' => [['type' => 'profile', 'path' => 'resource']], 'rules' => 'open']],
                ['id' => 'Bundle.entry:patient', 'path' => 'Bundle.entry', 'sliceName' => 'patient', 'min' => 0, 'max' => '1'],
                ['id' => 'Bundle.entry:patient.resource', 'path' => 'Bundle.entry.resource', 'type' => [['code' => 'Patient', 'profile' => [$mrn]]]],
            ]),
            self::profile($mrn, 'Patient', [
                ['id' => 'Patient.identifier', 'path' => 'Patient.identifier', 'slicing' => ['discriminator' => [['type' => 'value', 'path' => 'system']], 'rules' => 'open']],
                ['id' => 'Patient.identifier:mrn', 'path' => 'Patient.identifier', 'sliceName' => 'mrn', 'min' => 0, 'max' => '1'],
                ['id' => 'Patient.identifier:mrn.system', 'path' => 'Patient.identifier.system', 'fixedUri' => 'urn:mrn'],
            ]),
        );
        $bundle            = self::bundle($patient);
        $bundle['entry'][] = ['fullUrl' => 'urn:uuid:0d6c5b4a-3f21-4a3b-9e8f-7c3b2a4e1f0d', 'resource' => $patient];

        self::assertSame(
            [['entry', 'Slice "patient" on "entry" allows at most 1 item(s), but 2 matched.']],
            $this->findings($registry, $profile, $bundle),
        );
    }

    /**
     * A profile no registry holds cannot be checked, so its slice is undecided: no minimum, and its
     * items are not rejected by closed slicing.
     */
    public function testAProfileSliceOnAnUnknownProfileIsLeftUndecided(): void
    {
        $profile  = self::BASE . 'unknown-profile-bundle';
        $registry = self::registry(self::profile($profile, 'Bundle', [
            ['id' => 'Bundle.entry', 'path' => 'Bundle.entry', 'slicing' => ['discriminator' => [['type' => 'profile', 'path' => 'resource']], 'rules' => 'closed']],
            ['id' => 'Bundle.entry:patient', 'path' => 'Bundle.entry', 'sliceName' => 'patient', 'min' => 1],
            ['id' => 'Bundle.entry:patient.resource', 'path' => 'Bundle.entry.resource', 'type' => [['code' => 'Patient', 'profile' => [self::BASE . 'nowhere']]]],
        ]));

        self::assertSame([], $this->findings($registry, $profile, self::bundle(['resourceType' => 'Patient'])));
    }

    /**
     * The profile's error findings as path and message, sorted.
     *
     * @param array<string, mixed> $resource
     *
     * @return list<array{string, string}>
     */
    private function findings(InMemoryStructureDefinitionRegistry $registry, string $profile, array $resource, ?FHIRTerminologyClientInterface $client = null): array
    {
        $builder = FHIRValidationServiceBuilder::create()->withStructureDefinitionRegistry($registry);
        if ($client !== null) {
            $builder = $builder->withTerminologyClient($client);
        }

        $found = array_map(
            static fn (FHIRValidationViolation $v): array => [$v->path, $v->message],
            array_values(array_filter(
                $builder->build()->validate($this->serializer->deserialize((string) json_encode($resource)), [$profile])->violations,
                static fn (FHIRValidationViolation $v): bool => $v->profileGroup === $profile && $v->severity === 'error',
            )),
        );
        sort($found);

        return $found;
    }

    /**
     * @param array<string, mixed> $resource
     *
     * @return array<string, mixed>
     */
    private static function bundle(array $resource): array
    {
        return ['resourceType' => 'Bundle', 'type' => 'collection', 'entry' => [['fullUrl' => 'urn:uuid:7c3b2a4e-1f0d-4a3b-9e8f-2d6c5b4a3f21', 'resource' => $resource]]];
    }

    /**
     * @param array<string, mixed> $extra
     *
     * @return array<string, mixed>
     */
    private static function observation(array $extra): array
    {
        return ['resourceType' => 'Observation', 'status' => 'final', 'code' => ['text' => 'x'], ...$extra];
    }

    /**
     * @return array<string, mixed>
     */
    private static function medication(string $code): array
    {
        return ['resourceType' => 'Medication', 'code' => ['coding' => [['system' => self::PBS_SYSTEM, 'code' => $code]]]];
    }

    /**
     * @param list<array<string, mixed>> $elements Elements beneath the root
     *
     * @return array<string, mixed>
     */
    private static function profile(string $url, string $type, array $elements): array
    {
        $elements = [['id' => $type, 'path' => $type], ...$elements];

        return [
            'resourceType'   => 'StructureDefinition',
            'url'            => $url,
            'fhirVersion'    => '4.0.1',
            'kind'           => 'resource',
            'type'           => $type,
            'derivation'     => 'constraint',
            'baseDefinition' => self::CORE . $type,
            'snapshot'       => ['element' => $elements],
            'differential'   => ['element' => $elements],
        ];
    }

    /**
     * The given profiles, with a bare core definition for each type they constrain.
     *
     * @param array<string, mixed> ...$profiles
     */
    private static function registry(array ...$profiles): InMemoryStructureDefinitionRegistry
    {
        $definitions = [];
        foreach ($profiles as $profile) {
            $type                = (string) $profile['type'];
            $definitions[$type]  = [
                'resourceType' => 'StructureDefinition',
                'url'          => self::CORE . $type,
                'fhirVersion'  => '4.0.1',
                'kind'         => 'resource',
                'type'         => $type,
                'derivation'   => 'specialization',
                'snapshot'     => ['element' => [['id' => $type, 'path' => $type]]],
            ];
        }

        return new InMemoryStructureDefinitionRegistry([...array_values($definitions), ...$profiles]);
    }
}
