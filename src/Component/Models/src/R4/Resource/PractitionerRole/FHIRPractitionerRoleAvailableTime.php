<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description A collection of times the practitioner is available or performing this role at the location and/or healthcareservice.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'PractitionerRole', elementPath: 'PractitionerRole.availableTime', fhirVersion: 'R4')]
class FHIRPractitionerRoleAvailableTime extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDaysOfWeekType> daysOfWeek mon | tue | wed | thu | fri | sat | sun */
		public array $daysOfWeek = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean allDay Always available? e.g. 24 hour service */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean $allDay = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRTime availableStartTime Opening time of day (ignored if allDay = true) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRTime $availableStartTime = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRTime availableEndTime Closing time of day (ignored if allDay = true) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRTime $availableEndTime = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
