<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUnsignedInt;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDaysOfWeekType;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIREventTimingType;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUnitsOfTimeType;

/**
 * @description A set of rules that describe when the event is scheduled.
 */
#[FHIRComplexType(typeName: 'Timing.repeat', fhirVersion: 'R4B')]
class FHIRTimingRepeat extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRDuration|FHIRRange|FHIRPeriod|null boundsX Length/Range of lengths, or (Start and/or end) limits */
        public FHIRDuration|FHIRRange|FHIRPeriod|null $boundsX = null,
        /** @var FHIRPositiveInt|null count Number of times to repeat */
        public ?FHIRPositiveInt $count = null,
        /** @var FHIRPositiveInt|null countMax Maximum number of times to repeat */
        public ?FHIRPositiveInt $countMax = null,
        /** @var FHIRDecimal|null duration How long when it happens */
        public ?FHIRDecimal $duration = null,
        /** @var FHIRDecimal|null durationMax How long when it happens (Max) */
        public ?FHIRDecimal $durationMax = null,
        /** @var FHIRUnitsOfTimeType|null durationUnit s | min | h | d | wk | mo | a - unit of time (UCUM) */
        public ?FHIRUnitsOfTimeType $durationUnit = null,
        /** @var FHIRPositiveInt|null frequency Event occurs frequency times per period */
        public ?FHIRPositiveInt $frequency = null,
        /** @var FHIRPositiveInt|null frequencyMax Event occurs up to frequencyMax times per period */
        public ?FHIRPositiveInt $frequencyMax = null,
        /** @var FHIRDecimal|null period Event occurs frequency times per period */
        public ?FHIRDecimal $period = null,
        /** @var FHIRDecimal|null periodMax Upper limit of period (3-4 hours) */
        public ?FHIRDecimal $periodMax = null,
        /** @var FHIRUnitsOfTimeType|null periodUnit s | min | h | d | wk | mo | a - unit of time (UCUM) */
        public ?FHIRUnitsOfTimeType $periodUnit = null,
        /** @var array<FHIRDaysOfWeekType> dayOfWeek mon | tue | wed | thu | fri | sat | sun */
        public array $dayOfWeek = [],
        /** @var array<FHIRTime> timeOfDay Time of day for action */
        public array $timeOfDay = [],
        /** @var array<FHIREventTimingType> when Code for time period of occurrence */
        public array $when = [],
        /** @var FHIRUnsignedInt|null offset Minutes from event (before or after) */
        public ?FHIRUnsignedInt $offset = null,
    ) {
        parent::__construct($id, $extension);
    }
}
