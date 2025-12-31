<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Information about monthly recurring appointments.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Appointment', elementPath: 'Appointment.recurrenceTemplate.monthlyTemplate', fhirVersion: 'R5')]
class FHIRAppointmentRecurrenceTemplateMonthlyTemplate extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt dayOfMonth Recurs on a specific day of the month */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt $dayOfMonth = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding nthWeekOfMonth Indicates which week of the month the appointment should occur */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding $nthWeekOfMonth = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding dayOfWeek Indicates which day of the week the appointment should occur */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding $dayOfWeek = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt monthInterval Recurs every nth month */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt $monthInterval = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
