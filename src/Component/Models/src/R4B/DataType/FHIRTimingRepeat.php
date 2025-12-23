<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-complex-type Timing.repeat
 * @description A set of rules that describe when the event is scheduled.
 */
class FHIRTimingRepeat extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPeriod boundsX Length/Range of lengths, or (Start and/or end) limits */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPeriod|null $boundsX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPositiveInt count Number of times to repeat */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPositiveInt $count = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPositiveInt countMax Maximum number of times to repeat */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPositiveInt $countMax = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal duration How long when it happens */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal $duration = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal durationMax How long when it happens (Max) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal $durationMax = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUnitsOfTimeType durationUnit s | min | h | d | wk | mo | a - unit of time (UCUM) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUnitsOfTimeType $durationUnit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPositiveInt frequency Event occurs frequency times per period */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPositiveInt $frequency = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPositiveInt frequencyMax Event occurs up to frequencyMax times per period */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPositiveInt $frequencyMax = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal period Event occurs frequency times per period */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal $period = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal periodMax Upper limit of period (3-4 hours) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal $periodMax = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUnitsOfTimeType periodUnit s | min | h | d | wk | mo | a - unit of time (UCUM) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUnitsOfTimeType $periodUnit = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDaysOfWeekType> dayOfWeek mon | tue | wed | thu | fri | sat | sun */
		public array $dayOfWeek = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTime> timeOfDay Time of day for action */
		public array $timeOfDay = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIREventTimingType> when Code for time period of occurrence */
		public array $when = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUnsignedInt offset Minutes from event (before or after) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUnsignedInt $offset = null,
	) {
		parent::__construct($id, $extension);
	}
}
