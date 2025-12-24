<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Information about monthly recurring appointments.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Appointment', elementPath: 'Appointment.recurrenceTemplate.monthlyTemplate', fhirVersion: 'R5')]
class FHIRAppointmentRecurrenceTemplateMonthlyTemplate extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRPositiveInt|null dayOfMonth Recurs on a specific day of the month */
        public ?FHIRPositiveInt $dayOfMonth = null,
        /** @var FHIRCoding|null nthWeekOfMonth Indicates which week of the month the appointment should occur */
        public ?FHIRCoding $nthWeekOfMonth = null,
        /** @var FHIRCoding|null dayOfWeek Indicates which day of the week the appointment should occur */
        public ?FHIRCoding $dayOfWeek = null,
        /** @var FHIRPositiveInt|null monthInterval Recurs every nth month */
        #[NotBlank]
        public ?FHIRPositiveInt $monthInterval = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
