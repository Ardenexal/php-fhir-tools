<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Validation\FHIRChoiceVariantReader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;

/** Stand-in for a FHIR Quantity. */
final class ReaderQuantityStub
{
    public function __construct(public ?string $value = null)
    {
    }
}

/** Stand-in for a FHIR CodeableConcept, so a variant can be present but of the wrong type. */
final class ReaderConceptStub
{
    public function __construct(public ?string $text = null)
    {
    }
}

/** One repeat of a backbone element carrying its own choice. */
final class ReaderComponentStub
{
    public function __construct(
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                ['fhirType' => 'Quantity', 'propertyKind' => 'complex', 'phpType' => ReaderQuantityStub::class, 'jsonKey' => 'valueQuantity'],
            ],
        )]
        public ?ReaderQuantityStub $value = null,
    ) {
    }
}

/** Shaped like an Observation: a top-level choice plus a repeating backbone that carries one too. */
final class ReaderObservationStub
{
    /** @param list<ReaderComponentStub> $component */
    public function __construct(
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                ['fhirType' => 'Quantity', 'propertyKind' => 'complex', 'phpType' => ReaderQuantityStub::class, 'jsonKey' => 'valueQuantity'],
                ['fhirType' => 'CodeableConcept', 'propertyKind' => 'complex', 'phpType' => ReaderConceptStub::class, 'jsonKey' => 'valueCodeableConcept'],
            ],
        )]
        public ReaderQuantityStub|ReaderConceptStub|null $value = null,
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'complex', isArray: true)]
        public array $component = [],
    ) {
    }
}

/**
 * Covers reading element paths that name a choice variant, and the per-parent grouping that keeps
 * cardinality on a repeating element from being judged against the flattened total.
 */
final class FHIRChoiceVariantReaderTest extends TestCase
{
    private FHIRChoiceVariantReader $reader;

    protected function setUp(): void
    {
        $this->reader = new FHIRChoiceVariantReader(PropertyAccess::createPropertyAccessor());
    }

    /** A profile naming the concrete variant must reach the polymorphic property behind it. */
    public function testAChoiceVariantResolvesToTheValueItHolds(): void
    {
        $observation = new ReaderObservationStub(value: new ReaderQuantityStub('120'));

        self::assertSame([$observation->value], $this->reader->readGroups($observation, 'valueQuantity')[0]['occurrences']);
    }

    /** The property is populated, but with a different variant, so this one occurs zero times. */
    public function testAVariantOfAnotherTypeCountsAsAbsent(): void
    {
        $observation = new ReaderObservationStub(value: new ReaderConceptStub('normal'));

        self::assertSame([], $this->reader->readGroups($observation, 'valueQuantity')[0]['occurrences']);
    }

    public function testAnAbsentChoiceCountsAsZeroOccurrences(): void
    {
        self::assertSame([], $this->reader->readGroups(new ReaderObservationStub(), 'valueQuantity')[0]['occurrences']);
    }

    /**
     * The regression this grouping exists for: a blood pressure has two components, each with one
     * value. Flattened that reads as two values against a max of one, and a conforming document is
     * reported as invalid.
     */
    public function testEachRepeatOfAParentGetsItsOwnOccurrenceList(): void
    {
        $observation = new ReaderObservationStub(component: [
            new ReaderComponentStub(new ReaderQuantityStub('120')),
            new ReaderComponentStub(new ReaderQuantityStub('80')),
        ]);

        $groups = $this->reader->readGroups($observation, 'component.valueQuantity');

        self::assertCount(2, $groups);
        self::assertCount(1, $groups[0]['occurrences']);
        self::assertCount(1, $groups[1]['occurrences']);
    }

    /** No parent at all means an ancestor is absent, which is not the same as the element being absent. */
    public function testAnAbsentAncestorYieldsNoGroupsRatherThanAnEmptyOne(): void
    {
        self::assertSame([], $this->reader->readGroups(new ReaderObservationStub(), 'component.valueQuantity'));
    }

    public function testTheChoiceMarkerFormResolvesToo(): void
    {
        $observation = new ReaderObservationStub(value: new ReaderQuantityStub('120'));

        self::assertSame([$observation->value], $this->reader->readGroups($observation, 'value[x]')[0]['occurrences']);
    }
}
