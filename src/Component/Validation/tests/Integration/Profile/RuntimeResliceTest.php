<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Profile\StructureDefinitionCompiler;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationServiceBuilder;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\Profile\InMemoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\SliceDiscriminatorMatcher;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRSliceConstraintValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Reslices ("local/official") split the items of their slice ("local"): their cardinality counts
 * across those items, their rules bind the items that match them, and their own slicing's rules hold
 * among the slice's items.
 */
#[CoversClass(StructureDefinitionCompiler::class)]
#[CoversClass(FHIRSliceConstraintValidator::class)]
#[CoversClass(SliceDiscriminatorMatcher::class)]
final class RuntimeResliceTest extends TestCase
{
    private const string CORE = 'http://hl7.org/fhir/StructureDefinition/';

    private const string BASE = 'http://example.org/fhir/StructureDefinition/';

    private const string LOCAL = 'urn:example:local';

    private FHIRSerializationService $serializer;

    protected function setUp(): void
    {
        $this->serializer = FHIRSerializationService::createDefault(FhirVersion::R4);
    }

    public function testConformingReslicesHaveNoFindings(): void
    {
        $profile = self::BASE . 'resliced';

        self::assertSame([], $this->findings(self::registry(self::reslicedPatient($profile)), $profile, self::patient([
            self::identifier(self::LOCAL, 'official', '1'),
            self::identifier(self::LOCAL, 'old', '2'),
            self::identifier('urn:example:other', 'usual', '3'),
        ])));
    }

    public function testResliceMaximumCountsAcrossTheSlicesItems(): void
    {
        $profile = self::BASE . 'resliced';

        self::assertSame(
            [['identifier', 'Slice "local/official" on "identifier" allows at most 1 item(s), but 2 matched.']],
            $this->findings(self::registry(self::reslicedPatient($profile)), $profile, self::patient([
                self::identifier(self::LOCAL, 'official', '1'),
                self::identifier(self::LOCAL, 'official', '2'),
            ])),
        );
    }

    public function testResliceMinimumIsChecked(): void
    {
        $profile = self::BASE . 'resliced';

        self::assertSame(
            [['identifier', 'Slice "local/official" on "identifier" requires at least 1 item(s), but 0 matched.']],
            $this->findings(self::registry(self::reslicedPatient($profile)), $profile, self::patient([
                self::identifier(self::LOCAL, 'old', '2'),
            ])),
        );
    }

    public function testResliceRulesBindOnlyItsOwnItems(): void
    {
        $profile = self::BASE . 'resliced';

        self::assertSame(
            [['identifier[1].value', 'This collection should contain 1 element or more.|This collection should contain 1 elements or more.']],
            $this->findings(self::registry(self::reslicedPatient($profile)), $profile, self::patient([
                self::identifier(self::LOCAL, 'old', null),
                self::identifier(self::LOCAL, 'official', null),
            ])),
        );
    }

    public function testClosedReslicingRejectsSliceItemsMatchingNoReslice(): void
    {
        $profile = self::BASE . 'resliced';

        self::assertSame(
            [['identifier', 'Property "identifier" uses closed slicing but contains 1 item(s) matching no defined slice.']],
            $this->findings(self::registry(self::reslicedPatient($profile)), $profile, self::patient([
                self::identifier(self::LOCAL, 'official', '1'),
                self::identifier(self::LOCAL, 'temp', '9'),
                self::identifier('urn:example:other', 'temp', '3'),
            ])),
        );
    }

    /**
     * Modelled on the HL7 `reslicing-good-extensions` profile: the slice declares no slicing, so its
     * reslices are told apart by the element's discriminators, and the slice itself, which fixes no
     * `use`, takes the items any reslice takes.
     */
    public function testReslicesWithoutTheirOwnSlicingUseTheElementsDiscriminators(): void
    {
        $profile  = self::BASE . 'resliced-by-element';
        $registry = self::registry(self::profile($profile, [
            ['id' => 'Patient.identifier', 'path' => 'Patient.identifier', 'slicing' => [
                'discriminator' => [['type' => 'value', 'path' => 'system'], ['type' => 'value', 'path' => 'use']],
                'rules'         => 'open',
            ]],
            ['id' => 'Patient.identifier:local', 'path' => 'Patient.identifier', 'sliceName' => 'local', 'min' => 1],
            ['id' => 'Patient.identifier:local.system', 'path' => 'Patient.identifier.system', 'fixedUri' => self::LOCAL],
            ['id' => 'Patient.identifier:local/official', 'path' => 'Patient.identifier', 'sliceName' => 'local/official', 'max' => '1'],
            ['id' => 'Patient.identifier:local/official.use', 'path' => 'Patient.identifier.use', 'fixedCode' => 'official'],
            ['id' => 'Patient.identifier:local/old', 'path' => 'Patient.identifier', 'sliceName' => 'local/old', 'max' => '1'],
            ['id' => 'Patient.identifier:local/old.use', 'path' => 'Patient.identifier.use', 'fixedCode' => 'old'],
        ]));

        self::assertSame([], $this->findings($registry, $profile, self::patient([
            self::identifier(self::LOCAL, 'official', '1'),
            self::identifier(self::LOCAL, 'old', '2'),
        ])));
        self::assertSame(
            [['identifier', 'Slice "local/old" on "identifier" allows at most 1 item(s), but 2 matched.']],
            $this->findings($registry, $profile, self::patient([
                self::identifier(self::LOCAL, 'old', '1'),
                self::identifier(self::LOCAL, 'old', '2'),
            ])),
        );
        self::assertSame(
            [['identifier', 'Slice "local" on "identifier" requires at least 1 item(s), but 0 matched.']],
            $this->findings($registry, $profile, self::patient([self::identifier(self::LOCAL, 'usual', '1')])),
        );
    }

    /**
     * A profile that only reslices a slice its parent declares carries the reslices on that slice in
     * its own group.
     */
    public function testADerivedProfileReslicesAnInheritedSlice(): void
    {
        $parentUrl = self::BASE . 'parent';
        $childUrl  = self::BASE . 'child';
        $slicing   = ['discriminator' => [['type' => 'value', 'path' => 'system']], 'rules' => 'open'];
        $parent    = [
            ['id' => 'Patient.identifier', 'path' => 'Patient.identifier', 'slicing' => $slicing],
            ['id' => 'Patient.identifier:local', 'path' => 'Patient.identifier', 'sliceName' => 'local'],
            ['id' => 'Patient.identifier:local.system', 'path' => 'Patient.identifier.system', 'fixedUri' => self::LOCAL],
        ];
        $reslices = [
            ['id' => 'Patient.identifier:local', 'path' => 'Patient.identifier', 'sliceName' => 'local', 'slicing' => ['discriminator' => [['type' => 'value', 'path' => 'use']], 'rules' => 'open']],
            ['id' => 'Patient.identifier:local/official', 'path' => 'Patient.identifier', 'sliceName' => 'local/official', 'max' => '1'],
            ['id' => 'Patient.identifier:local/official.use', 'path' => 'Patient.identifier.use', 'fixedCode' => 'official'],
        ];

        $child                   = self::profile($childUrl, $reslices);
        $child['baseDefinition'] = $parentUrl;
        $child['snapshot']       = ['element' => [
            ['id' => 'Patient', 'path' => 'Patient'],
            $parent[0],
            [...$parent[1], 'slicing' => $reslices[0]['slicing']],
            $parent[2],
            $reslices[1],
            $reslices[2],
        ]];

        self::assertSame(
            [['identifier', 'Slice "local/official" on "identifier" allows at most 1 item(s), but 2 matched.']],
            $this->findings(self::registry(self::profile($parentUrl, $parent), $child), $childUrl, self::patient([
                self::identifier(self::LOCAL, 'official', '1'),
                self::identifier(self::LOCAL, 'official', '2'),
            ])),
        );
    }

    /**
     * The profile slices `identifier` by system, and its `local` slice is resliced by `use` with closed
     * rules: exactly one official identifier with a value, and at most one old one.
     *
     * @return array<string, mixed>
     */
    private static function reslicedPatient(string $url): array
    {
        return self::profile($url, [
            ['id' => 'Patient.identifier', 'path' => 'Patient.identifier', 'slicing' => ['discriminator' => [['type' => 'value', 'path' => 'system']], 'rules' => 'open']],
            ['id' => 'Patient.identifier:local', 'path' => 'Patient.identifier', 'sliceName' => 'local', 'slicing' => ['discriminator' => [['type' => 'value', 'path' => 'use']], 'rules' => 'closed']],
            ['id' => 'Patient.identifier:local.system', 'path' => 'Patient.identifier.system', 'fixedUri' => self::LOCAL],
            ['id' => 'Patient.identifier:local/official', 'path' => 'Patient.identifier', 'sliceName' => 'local/official', 'min' => 1, 'max' => '1'],
            ['id' => 'Patient.identifier:local/official.use', 'path' => 'Patient.identifier.use', 'fixedCode' => 'official'],
            ['id' => 'Patient.identifier:local/official.value', 'path' => 'Patient.identifier.value', 'min' => 1],
            ['id' => 'Patient.identifier:local/old', 'path' => 'Patient.identifier', 'sliceName' => 'local/old', 'max' => '1'],
            ['id' => 'Patient.identifier:local/old.use', 'path' => 'Patient.identifier.use', 'fixedCode' => 'old'],
        ]);
    }

    /**
     * @param array<string, mixed> $resource
     *
     * @return list<array{string, string}>
     */
    private function findings(InMemoryStructureDefinitionRegistry $registry, string $profile, array $resource): array
    {
        $found = array_map(
            static fn (FHIRValidationViolation $v): array => [$v->path, $v->message],
            array_values(array_filter(
                FHIRValidationServiceBuilder::create()->withStructureDefinitionRegistry($registry)->build()
                    ->validate($this->serializer->deserialize((string) json_encode($resource)), [$profile])->violations,
                static fn (FHIRValidationViolation $v): bool => $v->severity === 'error' && str_starts_with((string) $v->profileGroup, self::BASE),
            )),
        );
        sort($found);

        return $found;
    }

    /**
     * @return array<string, mixed>
     */
    private static function identifier(string $system, string $use, ?string $value): array
    {
        return array_filter(['system' => $system, 'use' => $use, 'value' => $value], static fn (?string $v): bool => $v !== null);
    }

    /**
     * @param list<array<string, mixed>> $identifiers
     *
     * @return array<string, mixed>
     */
    private static function patient(array $identifiers): array
    {
        return ['resourceType' => 'Patient', 'identifier' => $identifiers];
    }

    /**
     * @param list<array<string, mixed>> $elements Elements beneath the root
     *
     * @return array<string, mixed>
     */
    private static function profile(string $url, array $elements): array
    {
        $elements = [['id' => 'Patient', 'path' => 'Patient'], ...$elements];

        return [
            'resourceType'   => 'StructureDefinition',
            'url'            => $url,
            'fhirVersion'    => '4.0.1',
            'kind'           => 'resource',
            'type'           => 'Patient',
            'derivation'     => 'constraint',
            'baseDefinition' => self::CORE . 'Patient',
            'snapshot'       => ['element' => $elements],
            'differential'   => ['element' => $elements],
        ];
    }

    /**
     * The given profiles, with a bare core Patient definition.
     *
     * @param array<string, mixed> ...$profiles
     */
    private static function registry(array ...$profiles): InMemoryStructureDefinitionRegistry
    {
        return new InMemoryStructureDefinitionRegistry([
            [
                'resourceType' => 'StructureDefinition',
                'url'          => self::CORE . 'Patient',
                'fhirVersion'  => '4.0.1',
                'kind'         => 'resource',
                'type'         => 'Patient',
                'derivation'   => 'specialization',
                'snapshot'     => ['element' => [['id' => 'Patient', 'path' => 'Patient']]],
            ],
            ...array_values($profiles),
        ]);
    }
}
