<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The details of the recurrence pattern or template that is used to generate recurring appointments.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Appointment', elementPath: 'Appointment.recurrenceTemplate', fhirVersion: 'R5')]
class FHIRAppointmentRecurrenceTemplate extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null timezone The timezone of the occurrences */
        public ?FHIRCodeableConcept $timezone = null,
        /** @var FHIRCodeableConcept|null recurrenceType The frequency of the recurrence */
        #[NotBlank]
        public ?FHIRCodeableConcept $recurrenceType = null,
        /** @var FHIRDate|null lastOccurrenceDate The date when the recurrence should end */
        public ?FHIRDate $lastOccurrenceDate = null,
        /** @var FHIRPositiveInt|null occurrenceCount The number of planned occurrences */
        public ?FHIRPositiveInt $occurrenceCount = null,
        /** @var array<FHIRDate> occurrenceDate Specific dates for a recurring set of appointments (no template) */
        public array $occurrenceDate = [],
        /** @var FHIRAppointmentRecurrenceTemplateWeeklyTemplate|null weeklyTemplate Information about weekly recurring appointments */
        public ?FHIRAppointmentRecurrenceTemplateWeeklyTemplate $weeklyTemplate = null,
        /** @var FHIRAppointmentRecurrenceTemplateMonthlyTemplate|null monthlyTemplate Information about monthly recurring appointments */
        public ?FHIRAppointmentRecurrenceTemplateMonthlyTemplate $monthlyTemplate = null,
        /** @var FHIRAppointmentRecurrenceTemplateYearlyTemplate|null yearlyTemplate Information about yearly recurring appointments */
        public ?FHIRAppointmentRecurrenceTemplateYearlyTemplate $yearlyTemplate = null,
        /** @var array<FHIRDate> excludingDate Any dates that should be excluded from the series */
        public array $excludingDate = [],
        /** @var array<FHIRPositiveInt> excludingRecurrenceId Any recurrence IDs that should be excluded from the recurrence */
        public array $excludingRecurrenceId = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
