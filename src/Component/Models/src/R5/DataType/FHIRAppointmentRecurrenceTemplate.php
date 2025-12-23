<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Appointment.recurrenceTemplate
 * @description The details of the recurrence pattern or template that is used to generate recurring appointments.
 */
class FHIRAppointmentRecurrenceTemplate extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept timezone The timezone of the occurrences */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $timezone = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept recurrenceType The frequency of the recurrence */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $recurrenceType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate lastOccurrenceDate The date when the recurrence should end */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate $lastOccurrenceDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt occurrenceCount The number of planned occurrences */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt $occurrenceCount = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate> occurrenceDate Specific dates for a recurring set of appointments (no template) */
		public array $occurrenceDate = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAppointmentRecurrenceTemplateWeeklyTemplate weeklyTemplate Information about weekly recurring appointments */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAppointmentRecurrenceTemplateWeeklyTemplate $weeklyTemplate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAppointmentRecurrenceTemplateMonthlyTemplate monthlyTemplate Information about monthly recurring appointments */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAppointmentRecurrenceTemplateMonthlyTemplate $monthlyTemplate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAppointmentRecurrenceTemplateYearlyTemplate yearlyTemplate Information about yearly recurring appointments */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAppointmentRecurrenceTemplateYearlyTemplate $yearlyTemplate = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDate> excludingDate Any dates that should be excluded from the series */
		public array $excludingDate = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt> excludingRecurrenceId Any recurrence IDs that should be excluded from the recurrence */
		public array $excludingRecurrenceId = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
