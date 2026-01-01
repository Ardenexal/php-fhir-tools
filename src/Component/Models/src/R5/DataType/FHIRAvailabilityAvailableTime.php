<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @description Times the {item} is available.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Availability.availableTime', fhirVersion: 'R5')]
class FHIRAvailabilityAvailableTime extends FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDaysOfWeekType> daysOfWeek mon | tue | wed | thu | fri | sat | sun */
		public array $daysOfWeek = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean allDay Always available? i.e. 24 hour service */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $allDay = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRTime availableStartTime Opening time of day (ignored if allDay = true) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRTime $availableStartTime = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRTime availableEndTime Closing time of day (ignored if allDay = true) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRTime $availableEndTime = null,
	) {
		parent::__construct($id, $extension);
	}
}
