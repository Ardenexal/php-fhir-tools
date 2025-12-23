<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Appointment.recurrenceTemplate.monthlyTemplate
 * @description Information about monthly recurring appointments.
 */
class FHIRAppointmentRecurrenceTemplateMonthlyTemplate extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt dayOfMonth Recurs on a specific day of the month */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt $dayOfMonth = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding nthWeekOfMonth Indicates which week of the month the appointment should occur */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding $nthWeekOfMonth = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding dayOfWeek Indicates which day of the week the appointment should occur */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding $dayOfWeek = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt monthInterval Recurs every nth month */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt $monthInterval = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
