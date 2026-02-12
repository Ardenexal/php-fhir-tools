<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @description A set of rules that describe when the event is scheduled.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Timing.repeat', fhirVersion: 'R4')]
class TimingRepeat extends Element
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period boundsX Length/Range of lengths, or (Start and/or end) limits */
		public Duration|Range|Period|null $boundsX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive count Number of times to repeat */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive $count = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive countMax Maximum number of times to repeat */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive $countMax = null,
		/** @var null|float duration How long when it happens */
		public ?float $duration = null,
		/** @var null|float durationMax How long when it happens (Max) */
		public ?float $durationMax = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\UnitsOfTimeType durationUnit s | min | h | d | wk | mo | a - unit of time (UCUM) */
		public ?UnitsOfTimeType $durationUnit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive frequency Event occurs frequency times per period */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive $frequency = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive frequencyMax Event occurs up to frequencyMax times per period */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive $frequencyMax = null,
		/** @var null|float period Event occurs frequency times per period */
		public ?float $period = null,
		/** @var null|float periodMax Upper limit of period (3-4 hours) */
		public ?float $periodMax = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\UnitsOfTimeType periodUnit s | min | h | d | wk | mo | a - unit of time (UCUM) */
		public ?UnitsOfTimeType $periodUnit = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\DaysOfWeekType> dayOfWeek mon | tue | wed | thu | fri | sat | sun */
		public array $dayOfWeek = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive> timeOfDay Time of day for action */
		public array $timeOfDay = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\EventTimingType> when Code for time period of occurrence */
		public array $when = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive offset Minutes from event (before or after) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive $offset = null,
	) {
		parent::__construct($id, $extension);
	}
}
