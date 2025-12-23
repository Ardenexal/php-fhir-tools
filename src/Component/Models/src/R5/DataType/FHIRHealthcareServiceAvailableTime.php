<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element HealthcareService.availableTime
 * @description A collection of times that the Service Site is available.
 */
class FHIRHealthcareServiceAvailableTime extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDaysOfWeekType> daysOfWeek mon | tue | wed | thu | fri | sat | sun */
		public array $daysOfWeek = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean allDay Always available? e.g. 24 hour service */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean $allDay = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTime availableStartTime Opening time of day (ignored if allDay = true) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTime $availableStartTime = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTime availableEndTime Closing time of day (ignored if allDay = true) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTime $availableEndTime = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
