<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element PractitionerRole.availableTime
 * @description A collection of times the practitioner is available or performing this role at the location and/or healthcareservice.
 */
class FHIRPractitionerRoleAvailableTime extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
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
