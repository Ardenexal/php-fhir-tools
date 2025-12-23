<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-complex-type Availability.availableTime
 * @description Times the {item} is available.
 */
class FHIRAvailabilityAvailableTime extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDaysOfWeekType> daysOfWeek mon | tue | wed | thu | fri | sat | sun */
		public array $daysOfWeek = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean allDay Always available? i.e. 24 hour service */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $allDay = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTime availableStartTime Opening time of day (ignored if allDay = true) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTime $availableStartTime = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTime availableEndTime Closing time of day (ignored if allDay = true) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTime $availableEndTime = null,
	) {
		parent::__construct($id, $extension);
	}
}
