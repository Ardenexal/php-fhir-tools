<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Information about weekly recurring appointments.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Appointment', elementPath: 'Appointment.recurrenceTemplate.weeklyTemplate', fhirVersion: 'R5')]
class FHIRAppointmentRecurrenceTemplateWeeklyTemplate extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean monday Recurs on Mondays */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $monday = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean tuesday Recurs on Tuesday */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $tuesday = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean wednesday Recurs on Wednesday */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $wednesday = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean thursday Recurs on Thursday */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $thursday = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean friday Recurs on Friday */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $friday = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean saturday Recurs on Saturday */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $saturday = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean sunday Recurs on Sunday */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $sunday = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt weekInterval Recurs every nth week */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt $weekInterval = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
