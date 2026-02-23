<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;

/**
 * @description A set of rules that describe when the event is scheduled.
 */
#[FHIRComplexType(typeName: 'Timing.repeat', fhirVersion: 'R4')]
class TimingRepeat extends Element
{
    public const FHIR_PROPERTY_MAP = [
        'id' => [
            'fhirType'     => 'http://hl7.org/fhirpath/System.String',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'extension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'extension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'boundsX' => [
            'fhirType'     => 'choice',
            'propertyKind' => 'choice',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => true,
            'jsonKey'      => null,
            'variants'     => [
                [
                    'fhirType'     => 'Duration',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration',
                    'jsonKey'      => 'boundsDuration',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Range',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Range',
                    'jsonKey'      => 'boundsRange',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Period',
                    'jsonKey'      => 'boundsPeriod',
                    'isBuiltin'    => false,
                ],
            ],
        ],
        'count' => [
            'fhirType'     => 'positiveInt',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'countMax' => [
            'fhirType'     => 'positiveInt',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'duration' => [
            'fhirType'     => 'decimal',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'durationMax' => [
            'fhirType'     => 'decimal',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'durationUnit' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'frequency' => [
            'fhirType'     => 'positiveInt',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'frequencyMax' => [
            'fhirType'     => 'positiveInt',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'period' => [
            'fhirType'     => 'decimal',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'periodMax' => [
            'fhirType'     => 'decimal',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'periodUnit' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'dayOfWeek' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'timeOfDay' => [
            'fhirType'     => 'time',
            'propertyKind' => 'primitive',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'when' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'offset' => [
            'fhirType'     => 'unsignedInt',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
    ];

    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var Duration|Range|Period|null boundsX Length/Range of lengths, or (Start and/or end) limits */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'Duration',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration',
                    'jsonKey'      => 'boundsDuration',
                ],
                [
                    'fhirType'     => 'Range',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Range',
                    'jsonKey'      => 'boundsRange',
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Period',
                    'jsonKey'      => 'boundsPeriod',
                ],
            ],
        )]
        public Duration|Range|Period|null $boundsX = null,
        /** @var PositiveIntPrimitive|null count Number of times to repeat */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $count = null,
        /** @var PositiveIntPrimitive|null countMax Maximum number of times to repeat */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $countMax = null,
        /** @var float|null duration How long when it happens */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?float $duration = null,
        /** @var float|null durationMax How long when it happens (Max) */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?float $durationMax = null,
        /** @var UnitsOfTimeType|null durationUnit s | min | h | d | wk | mo | a - unit of time (UCUM) */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?UnitsOfTimeType $durationUnit = null,
        /** @var PositiveIntPrimitive|null frequency Event occurs frequency times per period */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $frequency = null,
        /** @var PositiveIntPrimitive|null frequencyMax Event occurs up to frequencyMax times per period */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $frequencyMax = null,
        /** @var float|null period Event occurs frequency times per period */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?float $period = null,
        /** @var float|null periodMax Upper limit of period (3-4 hours) */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?float $periodMax = null,
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
