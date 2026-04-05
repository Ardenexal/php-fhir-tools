<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\TimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UnsignedIntPrimitive;

/**
 * @description A set of rules that describe when the event is scheduled.
 */
#[FHIRComplexType(typeName: 'Timing.repeat', fhirVersion: 'R4B')]
class TimingRepeat extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var Duration|Range|Period|null bounds Length/Range of lengths, or (Start and/or end) limits */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'Duration',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Duration',
                    'jsonKey'      => 'boundsDuration',
                ],
                [
                    'fhirType'     => 'Range',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Range',
                    'jsonKey'      => 'boundsRange',
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period',
                    'jsonKey'      => 'boundsPeriod',
                ],
            ],
        )]
        public Duration|Range|Period|null $bounds = null,
        /** @var PositiveIntPrimitive|null count Number of times to repeat */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $count = null,
        /** @var PositiveIntPrimitive|null countMax Maximum number of times to repeat */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $countMax = null,
        /** @var numeric-string|null duration How long when it happens */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $duration = null,
        /** @var numeric-string|null durationMax How long when it happens (Max) */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $durationMax = null,
        /** @var UnitsOfTimeType|null durationUnit s | min | h | d | wk | mo | a - unit of time (UCUM) */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?UnitsOfTimeType $durationUnit = null,
        /** @var PositiveIntPrimitive|null frequency Event occurs frequency times per period */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $frequency = null,
        /** @var PositiveIntPrimitive|null frequencyMax Event occurs up to frequencyMax times per period */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $frequencyMax = null,
        /** @var numeric-string|null period Event occurs frequency times per period */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $period = null,
        /** @var numeric-string|null periodMax Upper limit of period (3-4 hours) */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $periodMax = null,
        /** @var UnitsOfTimeType|null periodUnit s | min | h | d | wk | mo | a - unit of time (UCUM) */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?UnitsOfTimeType $periodUnit = null,
        /** @var array<DaysOfWeekType> dayOfWeek mon | tue | wed | thu | fri | sat | sun */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isArray: true)]
        public array $dayOfWeek = [],
        /** @var array<TimePrimitive> timeOfDay Time of day for action */
        #[FhirProperty(fhirType: 'time', propertyKind: 'primitive', isArray: true)]
        public array $timeOfDay = [],
        /** @var array<EventTimingType> when Code for time period of occurrence */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isArray: true)]
        public array $when = [],
        /** @var UnsignedIntPrimitive|null offset Minutes from event (before or after) */
        #[FhirProperty(fhirType: 'unsignedInt', propertyKind: 'primitive')]
        public ?UnsignedIntPrimitive $offset = null,
    ) {
        parent::__construct($id, $extension);
    }
}
