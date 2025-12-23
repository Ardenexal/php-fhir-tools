<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-complex-type Timing.repeat
 * @description A set of rules that describe when the event is scheduled.
 */
class FHIRTimingRepeat extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod boundsX Length/Range of lengths, or (Start and/or end) limits */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod|null $boundsX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt count Number of times to repeat */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt $count = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt countMax Maximum number of times to repeat */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt $countMax = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDecimal duration How long when it happens */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDecimal $duration = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDecimal durationMax How long when it happens (Max) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDecimal $durationMax = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUnitsOfTimeType durationUnit s | min | h | d | wk | mo | a - unit of time (UCUM) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUnitsOfTimeType $durationUnit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt frequency Indicates the number of repetitions that should occur within a period. I.e. Event occurs frequency times per period */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt $frequency = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt frequencyMax Event occurs up to frequencyMax times per period */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt $frequencyMax = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDecimal period The duration to which the frequency applies. I.e. Event occurs frequency times per period */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDecimal $period = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDecimal periodMax Upper limit of period (3-4 hours) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDecimal $periodMax = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUnitsOfTimeType periodUnit s | min | h | d | wk | mo | a - unit of time (UCUM) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUnitsOfTimeType $periodUnit = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDaysOfWeekType> dayOfWeek mon | tue | wed | thu | fri | sat | sun */
		public array $dayOfWeek = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTime> timeOfDay Time of day for action */
		public array $timeOfDay = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREventTimingType> when Code for time period of occurrence */
		public array $when = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUnsignedInt offset Minutes from event (before or after) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUnsignedInt $offset = null,
	) {
		parent::__construct($id, $extension);
	}
}
