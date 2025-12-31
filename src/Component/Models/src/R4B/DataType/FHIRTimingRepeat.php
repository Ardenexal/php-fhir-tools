<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @description A set of rules that describe when the event is scheduled.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Timing.repeat', fhirVersion: 'R4B')]
class FHIRTimingRepeat extends FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod boundsX Length/Range of lengths, or (Start and/or end) limits */
		public FHIRDuration|FHIRRange|FHIRPeriod|null $boundsX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt count Number of times to repeat */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt $count = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt countMax Maximum number of times to repeat */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt $countMax = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal duration How long when it happens */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal $duration = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal durationMax How long when it happens (Max) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal $durationMax = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRUnitsOfTimeType durationUnit s | min | h | d | wk | mo | a - unit of time (UCUM) */
		public ?FHIRUnitsOfTimeType $durationUnit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt frequency Event occurs frequency times per period */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt $frequency = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt frequencyMax Event occurs up to frequencyMax times per period */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt $frequencyMax = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal period Event occurs frequency times per period */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal $period = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal periodMax Upper limit of period (3-4 hours) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal $periodMax = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRUnitsOfTimeType periodUnit s | min | h | d | wk | mo | a - unit of time (UCUM) */
		public ?FHIRUnitsOfTimeType $periodUnit = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDaysOfWeekType> dayOfWeek mon | tue | wed | thu | fri | sat | sun */
		public array $dayOfWeek = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRTime> timeOfDay Time of day for action */
		public array $timeOfDay = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIREventTimingType> when Code for time period of occurrence */
		public array $when = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUnsignedInt offset Minutes from event (before or after) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUnsignedInt $offset = null,
	) {
		parent::__construct($id, $extension);
	}
}
