<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt;

/**
 * @description Information about weekly recurring appointments.
 */
#[FHIRBackboneElement(parentResource: 'Appointment', elementPath: 'Appointment.recurrenceTemplate.weeklyTemplate', fhirVersion: 'R5')]
class FHIRAppointmentRecurrenceTemplateWeeklyTemplate extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRBoolean|null monday Recurs on Mondays */
        public ?FHIRBoolean $monday = null,
        /** @var FHIRBoolean|null tuesday Recurs on Tuesday */
        public ?FHIRBoolean $tuesday = null,
        /** @var FHIRBoolean|null wednesday Recurs on Wednesday */
        public ?FHIRBoolean $wednesday = null,
        /** @var FHIRBoolean|null thursday Recurs on Thursday */
        public ?FHIRBoolean $thursday = null,
        /** @var FHIRBoolean|null friday Recurs on Friday */
        public ?FHIRBoolean $friday = null,
        /** @var FHIRBoolean|null saturday Recurs on Saturday */
        public ?FHIRBoolean $saturday = null,
        /** @var FHIRBoolean|null sunday Recurs on Sunday */
        public ?FHIRBoolean $sunday = null,
        /** @var FHIRPositiveInt|null weekInterval Recurs every nth week */
        public ?FHIRPositiveInt $weekInterval = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
