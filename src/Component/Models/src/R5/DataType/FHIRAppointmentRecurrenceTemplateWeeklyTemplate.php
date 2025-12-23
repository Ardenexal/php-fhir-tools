<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Appointment.recurrenceTemplate.weeklyTemplate
 * @description Information about weekly recurring appointments.
 */
class FHIRAppointmentRecurrenceTemplateWeeklyTemplate extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean monday Recurs on Mondays */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $monday = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean tuesday Recurs on Tuesday */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $tuesday = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean wednesday Recurs on Wednesday */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $wednesday = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean thursday Recurs on Thursday */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $thursday = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean friday Recurs on Friday */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $friday = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean saturday Recurs on Saturday */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $saturday = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean sunday Recurs on Sunday */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $sunday = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt weekInterval Recurs every nth week */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt $weekInterval = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
