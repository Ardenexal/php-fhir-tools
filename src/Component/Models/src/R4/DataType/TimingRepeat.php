<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;

/**
 * @description A set of rules that describe when the event is scheduled.
 */
#[FHIRComplexType(typeName: 'Timing.repeat', fhirVersion: 'R4')]
class TimingRepeat extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var Duration|Range|Period|null boundsX Length/Range of lengths, or (Start and/or end) limits */
        public Duration|Range|Period|null $boundsX = null,
        /** @var PositiveIntPrimitive|null count Number of times to repeat */
        public ?PositiveIntPrimitive $count = null,
        /** @var PositiveIntPrimitive|null countMax Maximum number of times to repeat */
        public ?PositiveIntPrimitive $countMax = null,
        /** @var float|null duration How long when it happens */
        public ?float $duration = null,
        /** @var float|null durationMax How long when it happens (Max) */
        public ?float $durationMax = null,
        /** @var UnitsOfTimeType|null durationUnit s | min | h | d | wk | mo | a - unit of time (UCUM) */
        public ?UnitsOfTimeType $durationUnit = null,
        /** @var PositiveIntPrimitive|null frequency Event occurs frequency times per period */
        public ?PositiveIntPrimitive $frequency = null,
        /** @var PositiveIntPrimitive|null frequencyMax Event occurs up to frequencyMax times per period */
        public ?PositiveIntPrimitive $frequencyMax = null,
        /** @var float|null period Event occurs frequency times per period */
        public ?float $period = null,
        /** @var float|null periodMax Upper limit of period (3-4 hours) */
        public ?float $periodMax = null,
        /** @var UnitsOfTimeType|null periodUnit s | min | h | d | wk | mo | a - unit of time (UCUM) */
        public ?UnitsOfTimeType $periodUnit = null,
        /** @var array<DaysOfWeekType> dayOfWeek mon | tue | wed | thu | fri | sat | sun */
        public array $dayOfWeek = [],
        /** @var array<TimePrimitive> timeOfDay Time of day for action */
        public array $timeOfDay = [],
        /** @var array<EventTimingType> when Code for time period of occurrence */
        public array $when = [],
        /** @var UnsignedIntPrimitive|null offset Minutes from event (before or after) */
        public ?UnsignedIntPrimitive $offset = null,
    ) {
        parent::__construct($id, $extension);
    }
}
