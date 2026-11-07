<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Models\R4\Profile\ObservationVitalsignsProfile;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ObservationResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Validation\Profile\InMemoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileAttributeReader;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileResolver;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RuntimeProfileAttributeReader::class)]
#[CoversClass(RuntimeProfileResolver::class)]
final class RuntimeProfileAttributeReaderTest extends TestCase
{
    private const string CORE = 'http://hl7.org/fhir/StructureDefinition/';

    private const string SLICED = 'http://example.org/fhir/StructureDefinition/sliced';

    private RuntimeProfileResolver $resolver;

    private RuntimeProfileAttributeReader $reader;

    protected function setUp(): void
    {
        $this->resolver = new RuntimeProfileResolver(new InMemoryStructureDefinitionRegistry([
            self::observationProfile(self::CORE . 'Observation', 'specialization', []),
            self::observationProfile(self::SLICED, 'constraint', self::categorySlice()),
            self::observationProfile(self::CORE . 'vitalsigns', 'constraint', self::categorySlice()),
        ]));
        $this->reader = new RuntimeProfileAttributeReader($this->resolver);
    }

    public function testAddsNothingBeforeAProfileIsActivated(): void
    {
        self::assertSame([], $this->reader->classAttributesInHierarchy(new ObservationResource(), FHIRSliceConstraint::class));
    }

    public function testAddsSlicesOfResolvedProfilesForTheSubjectsType(): void
    {
        $this->activate(self::SLICED);

        $slices = $this->reader->classAttributesInHierarchy(new ObservationResource(), FHIRSliceConstraint::class);
        $rules  = $this->reader->classAttributesInHierarchy(ObservationResource::class, FHIRSlicingRules::class);

        self::assertCount(1, $slices);
        self::assertSame('lab', $slices[0]->sliceName);
        self::assertSame([self::SLICED], $slices[0]->groups);
        self::assertCount(1, $rules);
        self::assertSame('closed', $rules[0]->rules);
        self::assertSame([], $this->reader->classAttributesInHierarchy(new PatientResource(), FHIRSliceConstraint::class));
    }

    public function testReusesInstancesAcrossReads(): void
    {
        $this->activate(self::SLICED);

        self::assertSame(
            $this->reader->classAttributesInHierarchy(new ObservationResource(), FHIRSliceConstraint::class)[0],
            $this->reader->classAttributesInHierarchy(new ObservationResource(), FHIRSliceConstraint::class)[0],
        );
    }

    public function testLeavesOutProfilesTheClassDeclares(): void
    {
        $this->activate(self::CORE . 'vitalsigns');

        $declared = array_filter(
            $this->reader->classAttributesInHierarchy(ObservationVitalsignsProfile::class, FHIRSliceConstraint::class),
            static fn (FHIRSliceConstraint $slice): bool => $slice->sliceName === 'lab',
        );

        self::assertSame([], $declared);
    }

    public function testOtherAttributesPassThrough(): void
    {
        $this->activate(self::SLICED);

        self::assertSame([], $this->reader->classAttributesInHierarchy(new ObservationResource(), FHIRProfile::class));
        self::assertSame(
            [self::CORE . 'vitalsigns'],
            array_map(
                static fn (FHIRProfile $profile): string => $profile->profileUrl,
                $this->reader->classAttributesInHierarchy(ObservationVitalsignsProfile::class, FHIRProfile::class),
            ),
        );
    }

    public function testServesOnlyActivatedProfiles(): void
    {
        $this->resolver->resolve(self::SLICED, 'R4');

        self::assertSame([], $this->reader->classAttributesInHierarchy(new ObservationResource(), FHIRSliceConstraint::class));
    }

    private function activate(string $url): void
    {
        $resolution = $this->resolver->resolve($url, 'R4');
        self::assertTrue($resolution->isResolved(), (string) $resolution->message);
        $this->resolver->activate('R4', 'Observation', $resolution->chain);
    }

    /**
     * @param list<array<string, mixed>> $elements
     *
     * @return array<string, mixed>
     */
    private static function observationProfile(string $url, string $derivation, array $elements): array
    {
        $elements = [['id' => 'Observation', 'path' => 'Observation'], ...$elements];

        return [
            'resourceType'   => 'StructureDefinition',
            'url'            => $url,
            'fhirVersion'    => '4.0.1',
            'kind'           => 'resource',
            'type'           => 'Observation',
            'baseDefinition' => self::CORE . 'Observation',
            'derivation'     => $derivation,
            'snapshot'       => ['element' => $elements],
            'differential'   => ['element' => $elements],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private static function categorySlice(): array
    {
        return [
            [
                'id'      => 'Observation.category',
                'path'    => 'Observation.category',
                'slicing' => ['discriminator' => [['type' => 'value', 'path' => 'coding.code']], 'rules' => 'closed'],
            ],
            ['id' => 'Observation.category:lab', 'path' => 'Observation.category', 'sliceName' => 'lab', 'min' => 1, 'max' => '1'],
            [
                'id'        => 'Observation.category:lab.coding.code',
                'path'      => 'Observation.category.coding.code',
                'fixedCode' => 'laboratory',
            ],
        ];
    }
}
