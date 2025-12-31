<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description The details of the recurrence pattern or template that is used to generate recurring appointments.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Appointment', elementPath: 'Appointment.recurrenceTemplate', fhirVersion: 'R5')]
class FHIRAppointmentRecurrenceTemplate extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept timezone The timezone of the occurrences */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $timezone = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept recurrenceType The frequency of the recurrence */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $recurrenceType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate lastOccurrenceDate The date when the recurrence should end */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate $lastOccurrenceDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt occurrenceCount The number of planned occurrences */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt $occurrenceCount = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate> occurrenceDate Specific dates for a recurring set of appointments (no template) */
		public array $occurrenceDate = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAppointmentRecurrenceTemplateWeeklyTemplate weeklyTemplate Information about weekly recurring appointments */
		public ?FHIRAppointmentRecurrenceTemplateWeeklyTemplate $weeklyTemplate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAppointmentRecurrenceTemplateMonthlyTemplate monthlyTemplate Information about monthly recurring appointments */
		public ?FHIRAppointmentRecurrenceTemplateMonthlyTemplate $monthlyTemplate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAppointmentRecurrenceTemplateYearlyTemplate yearlyTemplate Information about yearly recurring appointments */
		public ?FHIRAppointmentRecurrenceTemplateYearlyTemplate $yearlyTemplate = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate> excludingDate Any dates that should be excluded from the series */
		public array $excludingDate = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt> excludingRecurrenceId Any recurrence IDs that should be excluded from the recurrence */
		public array $excludingRecurrenceId = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
