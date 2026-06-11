<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;

/**
 * @description A set of rules that describe when the event is scheduled.
 */
#[FHIRComplexType(typeName: 'Timing.repeat', fhirVersion: 'R4')]
#[FHIRPathInvariant(
    key: 'tim-1',
    severity: 'error',
    expression: 'duration.empty() or durationUnit.exists()',
    human: 'if there\'s a duration, there needs to be duration units',
)]
#[FHIRPathInvariant(
    key: 'tim-2',
    severity: 'error',
    expression: 'period.empty() or periodUnit.exists()',
    human: 'if there\'s a period, there needs to be period units',
)]
#[FHIRPathInvariant(
    key: 'tim-4',
    severity: 'error',
    expression: 'duration.exists() implies duration >= 0',
    human: 'duration SHALL be a non-negative value',
)]
#[FHIRPathInvariant(
    key: 'tim-5',
    severity: 'error',
    expression: 'period.exists() implies period >= 0',
    human: 'period SHALL be a non-negative value',
)]
#[FHIRPathInvariant(
    key: 'tim-6',
    severity: 'error',
    expression: 'periodMax.empty() or period.exists()',
    human: 'If there\'s a periodMax, there must be a period',
)]
#[FHIRPathInvariant(
    key: 'tim-7',
    severity: 'error',
    expression: 'durationMax.empty() or duration.exists()',
    human: 'If there\'s a durationMax, there must be a duration',
)]
#[FHIRPathInvariant(
    key: 'tim-8',
    severity: 'error',
    expression: 'countMax.empty() or count.exists()',
    human: 'If there\'s a countMax, there must be a count',
)]
#[FHIRPathInvariant(
    key: 'tim-9',
    severity: 'error',
    expression: 'offset.empty() or (when.exists() and ((when in (\'C\' | \'CM\' | \'CD\' | \'CV\')).not()))',
    human: 'If there\'s an offset, there must be a when (and not C, CM, CD, CV)',
)]
#[FHIRPathInvariant(
    key: 'tim-10',
    severity: 'error',
    expression: 'timeOfDay.empty() or when.empty()',
    human: 'If there\'s a timeOfDay, there cannot be a when, or vice versa',
)]
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
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/units-of-time|4.0.1', strength: 'required')]
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
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/units-of-time|4.0.1', strength: 'required')]
        public ?UnitsOfTimeType $periodUnit = null,
        /** @var array<DaysOfWeekType> dayOfWeek mon | tue | wed | thu | fri | sat | sun */
        #[FhirProperty(
            fhirType: 'code',
            propertyKind: 'primitive',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\DaysOfWeekType',
        )]
        #[FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/days-of-week|4.0.1', strength: 'required')]
        public array $dayOfWeek = [],
        /** @var array<TimePrimitive> timeOfDay Time of day for action */
        #[FhirProperty(
            fhirType: 'time',
            propertyKind: 'primitive',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive',
        )]
        public array $timeOfDay = [],
        /** @var array<EventTimingType> when Code for time period of occurrence */
        #[FhirProperty(
            fhirType: 'code',
            propertyKind: 'primitive',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\EventTimingType',
        )]
        #[FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/event-timing|4.0.1', strength: 'required')]
        public array $when = [],
        /** @var UnsignedIntPrimitive|null offset Minutes from event (before or after) */
        #[FhirProperty(fhirType: 'unsignedInt', propertyKind: 'primitive')]
        public ?UnsignedIntPrimitive $offset = null,
    ) {
        parent::__construct($id, $extension);
    }
}
