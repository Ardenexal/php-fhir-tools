<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Profile;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationServiceBuilder;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\Profile\InMemoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileConformance;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRAnyOfProfilesValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * An element whose types name several profiles, without allowing the base type, must conform to at
 * least one of them. Each profile is checked on the side, as `profile` slice discriminators are.
 */
#[CoversClass(FHIRAnyOfProfilesValidator::class)]
#[CoversClass(RuntimeProfileConformance::class)]
final class RuntimeAnyOfProfilesTest extends TestCase
{
    private const string CORE = 'http://hl7.org/fhir/StructureDefinition/';

    private const string BASE = 'http://example.org/fhir/StructureDefinition/';

    private FHIRSerializationService $serializer;

    protected function setUp(): void
    {
        $this->serializer = FHIRSerializationService::createDefault(FhirVersion::R4);
    }

    /**
     * Modelled on the HL7 `patient-address-choice-profile` case: an address must be a city address
     * or a postcode address.
     */
    public function testAnAddressMustConformToOneOfTheProfiles(): void
    {
        $profile  = self::BASE . 'two-address-patient';
        $registry = $this->addressRegistry($profile, [['code' => 'Address', 'profile' => [self::BASE . 'city-address', self::BASE . 'postcode-address']]]);

        self::assertSame([], $this->errors($registry, $profile, self::patient(['city' => 'Melbourne'])));
        self::assertSame([], $this->errors($registry, $profile, self::patient(['postalCode' => '3000'])));

        // As Java reports it: that no profile matched, then what each profile found
        self::assertSame(
            [
                ['address[0]', 'Unable to find a match for the specified profile among choices: ' . self::BASE . 'city-address, ' . self::BASE . 'postcode-address'],
                ['address[0].city', 'This collection should contain 1 element or more.|This collection should contain 1 elements or more. (validating against ' . self::BASE . 'city-address)'],
                ['address[0].postalCode', 'This collection should contain 1 element or more.|This collection should contain 1 elements or more. (validating against ' . self::BASE . 'postcode-address)'],
            ],
            $this->errors($registry, $profile, self::patient(['country' => 'AU'])),
        );
    }

    /**
     * A value that matches more than one profile is valid, but which one it follows is ambiguous, so
     * it gets a warning, as Java gives it.
     */
    public function testMatchingSeveralProfilesIsAWarning(): void
    {
        $profile  = self::BASE . 'two-address-patient';
        $registry = $this->addressRegistry($profile, [['code' => 'Address', 'profile' => [self::BASE . 'city-address', self::BASE . 'postcode-address']]]);
        $resource = $this->serializer->deserialize((string) json_encode(self::patient(['city' => 'Melbourne', 'postalCode' => '3000'])));

        $violations = FHIRValidationServiceBuilder::create()->withStructureDefinitionRegistry($registry)->build()->validate($resource, [$profile])->violations;
        $warnings   = array_values(array_filter($violations, static fn (FHIRValidationViolation $v): bool => $v->path === 'address[0]'));

        self::assertCount(1, $warnings);
        self::assertSame('warning', $warnings[0]->severity);
        self::assertSame('Found multiple matching profiles among 2 choices: ' . self::BASE . 'city-address, ' . self::BASE . 'postcode-address', $warnings[0]->message);
    }

    /**
     * Each address is checked on its own, so one bad address among good ones is reported at its index.
     */
    public function testEachRepeatIsCheckedOnItsOwn(): void
    {
        $profile  = self::BASE . 'two-address-patient';
        $registry = $this->addressRegistry($profile, [['code' => 'Address', 'profile' => [self::BASE . 'city-address', self::BASE . 'postcode-address']]]);

        $errors = $this->errors($registry, $profile, ['resourceType' => 'Patient', 'address' => [['city' => 'Melbourne'], ['country' => 'AU'], ['postalCode' => '3000']]]);

        self::assertSame(['address[1]'], self::noMatchPaths($errors));
        self::assertSame(['address[1]', 'address[1].city', 'address[1].postalCode'], array_column($errors, 0));
    }

    /**
     * Listing the base type beside the profiles lets any address through, so nothing is checked.
     */
    public function testAllowingTheBaseTypeChecksNothing(): void
    {
        $profile  = self::BASE . 'open-address-patient';
        $registry = $this->addressRegistry($profile, [['code' => 'Address', 'profile' => [self::CORE . 'Address', self::BASE . 'city-address', self::BASE . 'postcode-address']]]);

        self::assertSame([], $this->errors($registry, $profile, self::patient(['country' => 'AU'])));
    }

    /**
     * Profiles no registry holds cannot be checked: the element is reported as not checked, at warning
     * severity, and is not an error.
     */
    public function testUnknownProfilesAreReportedAsNotChecked(): void
    {
        $profile  = self::BASE . 'unknown-address-patient';
        $registry = $this->addressRegistry($profile, [['code' => 'Address', 'profile' => [self::BASE . 'nowhere-a', self::BASE . 'nowhere-b']]]);
        $resource = $this->serializer->deserialize((string) json_encode(self::patient(['country' => 'AU'])));

        $violations = FHIRValidationServiceBuilder::create()->withStructureDefinitionRegistry($registry)->build()->validate($resource, [$profile])->violations;
        $notChecked = array_values(array_filter($violations, static fn (FHIRValidationViolation $v): bool => $v->code === FHIRViolationCode::PROFILE_NOT_CHECKED && $v->path === 'address[0]'));

        self::assertSame([], $this->errors($registry, $profile, self::patient(['country' => 'AU'])));
        self::assertCount(1, $notChecked);
        self::assertSame('warning', $notChecked[0]->severity);
    }

    /**
     * Modelled on the HL7 `profile-slicing-multiple-profile` case: a slice's resource may be a
     * profiled Practitioner or a profiled PractitionerRole.
     */
    public function testASliceResourceMustConformToOneOfItsTypesProfiles(): void
    {
        $profile      = self::BASE . 'care-team-bundle';
        $practitioner = self::BASE . 'named-practitioner';
        $role         = self::BASE . 'active-role';
        $registry     = self::registry(
            self::definition($profile, 'resource', 'Bundle', [
                ['id' => 'Bundle.entry', 'path' => 'Bundle.entry', 'slicing' => ['discriminator' => [['type' => 'type', 'path' => 'resource']], 'rules' => 'open']],
                ['id' => 'Bundle.entry:carer', 'path' => 'Bundle.entry', 'sliceName' => 'carer', 'min' => 0, 'max' => '*'],
                ['id' => 'Bundle.entry:carer.resource', 'path' => 'Bundle.entry.resource', 'type' => [
                    ['code' => 'Practitioner', 'profile' => [$practitioner]],
                    ['code' => 'PractitionerRole', 'profile' => [$role]],
                ]],
            ]),
            self::definition($practitioner, 'resource', 'Practitioner', [['id' => 'Practitioner.name', 'path' => 'Practitioner.name', 'min' => 1]]),
            self::definition($role, 'resource', 'PractitionerRole', [['id' => 'PractitionerRole.active', 'path' => 'PractitionerRole.active', 'min' => 1]]),
        );

        self::assertSame([], $this->errors($registry, $profile, self::bundle(['resourceType' => 'Practitioner', 'name' => [['family' => 'Nguyen']]])));
        self::assertSame([], $this->errors($registry, $profile, self::bundle(['resourceType' => 'PractitionerRole', 'active' => true])));
        // A Practitioner is not a PractitionerRole, which that profile's error says
        self::assertSame(
            [
                ['entry[0].resource', 'The profile constrains PractitionerRole, not Practitioner (validating against ' . $role . ')'],
                ['entry[0].resource', 'Unable to find a match for the specified profile among choices: ' . $practitioner . ', ' . $role],
                ['entry[0].resource.name', 'This collection should contain 1 element or more.|This collection should contain 1 elements or more. (validating against ' . $practitioner . ')'],
            ],
            $this->errors($registry, $profile, self::bundle(['resourceType' => 'Practitioner'])),
        );
    }

    /**
     * A slice's choice of profiles, where one profile slices an element of its own, still reports:
     * that profile's slicing runs through the same slice validator as the outer slice's rules.
     */
    public function testASliceChoiceWhoseProfileSlicesKeepsItsFindings(): void
    {
        $profile      = self::BASE . 'care-team-bundle';
        $practitioner = self::BASE . 'named-practitioner';
        $role         = self::BASE . 'active-role';
        $registry     = self::registry(
            self::definition($profile, 'resource', 'Bundle', [
                ['id' => 'Bundle.entry', 'path' => 'Bundle.entry', 'slicing' => ['discriminator' => [['type' => 'type', 'path' => 'resource']], 'rules' => 'open']],
                ['id' => 'Bundle.entry:carer', 'path' => 'Bundle.entry', 'sliceName' => 'carer', 'min' => 0, 'max' => '*'],
                ['id' => 'Bundle.entry:carer.resource', 'path' => 'Bundle.entry.resource', 'type' => [
                    ['code' => 'Practitioner', 'profile' => [$practitioner]],
                    ['code' => 'PractitionerRole', 'profile' => [$role]],
                ]],
            ]),
            self::definition($practitioner, 'resource', 'Practitioner', [
                ['id' => 'Practitioner.identifier', 'path' => 'Practitioner.identifier', 'slicing' => ['discriminator' => [['type' => 'value', 'path' => 'system']], 'rules' => 'open']],
                ['id' => 'Practitioner.identifier:local', 'path' => 'Practitioner.identifier', 'sliceName' => 'local', 'min' => 0, 'max' => '1'],
                ['id' => 'Practitioner.identifier:local.system', 'path' => 'Practitioner.identifier.system', 'fixedUri' => 'urn:local'],
                ['id' => 'Practitioner.name', 'path' => 'Practitioner.name', 'min' => 1],
            ]),
            self::definition($role, 'resource', 'PractitionerRole', [['id' => 'PractitionerRole.active', 'path' => 'PractitionerRole.active', 'min' => 1]]),
        );

        self::assertSame([], $this->errors($registry, $profile, self::bundle(['resourceType' => 'Practitioner', 'name' => [['family' => 'Nguyen']]])));
        self::assertSame(['entry[0].resource'], self::noMatchPaths($this->errors($registry, $profile, self::bundle(['resourceType' => 'Practitioner']))));
    }

    /**
     * A choice whose profiles have a choice of their own still reports the outer finding: the inner
     * check runs through the same validator, and must not swallow the outer one's findings.
     */
    public function testANestedChoiceKeepsTheOuterFinding(): void
    {
        $profile   = self::BASE . 'patient-bundle';
        $addressed = self::BASE . 'two-address-patient';
        $named     = self::BASE . 'named-patient';
        $registry  = self::registry(
            self::definition($profile, 'resource', 'Bundle', [
                ['id' => 'Bundle.entry.resource', 'path' => 'Bundle.entry.resource', 'type' => [['code' => 'Patient', 'profile' => [$addressed, $named]]]],
            ]),
            self::definition($addressed, 'resource', 'Patient', [
                ['id' => 'Patient.address', 'path' => 'Patient.address', 'min' => 1, 'type' => [['code' => 'Address', 'profile' => [self::BASE . 'city-address', self::BASE . 'postcode-address']]]],
            ]),
            self::definition($named, 'resource', 'Patient', [['id' => 'Patient.name', 'path' => 'Patient.name', 'min' => 1]]),
            self::definition(self::BASE . 'city-address', 'complex-type', 'Address', [['id' => 'Address.city', 'path' => 'Address.city', 'min' => 1]]),
            self::definition(self::BASE . 'postcode-address', 'complex-type', 'Address', [['id' => 'Address.postalCode', 'path' => 'Address.postalCode', 'min' => 1]]),
        );

        self::assertSame([], $this->errors($registry, $profile, self::bundle(['resourceType' => 'Patient', 'address' => [['city' => 'Melbourne']]])));
        self::assertSame(['entry.resource'], self::noMatchPaths($this->errors($registry, $profile, self::bundle(['resourceType' => 'Patient', 'address' => [['country' => 'AU']]]))));
    }

    /**
     * A Patient profile typing `address` with $types, and the two address profiles it can name.
     *
     * @param list<array<string, mixed>> $types
     */
    private function addressRegistry(string $profile, array $types): InMemoryStructureDefinitionRegistry
    {
        return self::registry(
            self::definition($profile, 'resource', 'Patient', [['id' => 'Patient.address', 'path' => 'Patient.address', 'type' => $types]]),
            self::definition(self::BASE . 'city-address', 'complex-type', 'Address', [['id' => 'Address.city', 'path' => 'Address.city', 'min' => 1]]),
            self::definition(self::BASE . 'postcode-address', 'complex-type', 'Address', [['id' => 'Address.postalCode', 'path' => 'Address.postalCode', 'min' => 1]]),
        );
    }

    /**
     * The profile's error findings as path and message, sorted.
     *
     * @param array<string, mixed> $resource
     *
     * @return list<array{string, string}>
     */
    private function errors(InMemoryStructureDefinitionRegistry $registry, string $profile, array $resource): array
    {
        $violations = FHIRValidationServiceBuilder::create()
            ->withStructureDefinitionRegistry($registry)
            ->build()
            ->validate($this->serializer->deserialize((string) json_encode($resource)), [$profile])
            ->violations;

        $found = array_map(
            static fn (FHIRValidationViolation $v): array => [$v->path, $v->message],
            array_values(array_filter($violations, static fn (FHIRValidationViolation $v): bool => $v->profileGroup === $profile && $v->severity === 'error')),
        );
        sort($found);

        return $found;
    }

    /**
     * Where each "no profile matched" finding is.
     *
     * @param list<array{string, string}> $errors
     *
     * @return list<string>
     */
    private static function noMatchPaths(array $errors): array
    {
        return array_values(array_map(
            static fn (array $error): string => $error[0],
            array_filter($errors, static fn (array $error): bool => str_starts_with($error[1], 'Unable to find a match') && !str_contains($error[1], '(validating against')),
        ));
    }

    /**
     * @param array<string, mixed> $address
     *
     * @return array<string, mixed>
     */
    private static function patient(array $address): array
    {
        return ['resourceType' => 'Patient', 'address' => [$address]];
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
     * A constraint StructureDefinition whose snapshot and differential both hold $elements.
     *
     * @param list<array<string, mixed>> $elements Elements beneath the root
     *
     * @return array<string, mixed>
     */
    private static function definition(string $url, string $kind, string $type, array $elements): array
    {
        $elements = [['id' => $type, 'path' => $type], ...$elements];

        return [
            'resourceType'   => 'StructureDefinition',
            'url'            => $url,
            'fhirVersion'    => '4.0.1',
            'kind'           => $kind,
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
            $type               = (string) $profile['type'];
            $definitions[$type] = [
                'resourceType' => 'StructureDefinition',
                'url'          => self::CORE . $type,
                'fhirVersion'  => '4.0.1',
                'kind'         => $profile['kind'],
                'type'         => $type,
                'derivation'   => 'specialization',
                'snapshot'     => ['element' => [['id' => $type, 'path' => $type]]],
            ];
        }

        return new InMemoryStructureDefinitionRegistry([...array_values($definitions), ...$profiles]);
    }
}
