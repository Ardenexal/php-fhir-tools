<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description List of participants involved in the appointment.
 */
#[FHIRBackboneElement(parentResource: 'Appointment', elementPath: 'Appointment.participant', fhirVersion: 'R4')]
class FHIRAppointmentParticipant extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRCodeableConcept> type Role of participant in the appointment */
        public array $type = [],
        /** @var FHIRReference|null actor Person, Location/HealthcareService or Device */
        public ?FHIRReference $actor = null,
        /** @var FHIRParticipantRequiredType|null required required | optional | information-only */
        public ?FHIRParticipantRequiredType $required = null,
        /** @var FHIRParticipationStatusType|null status accepted | declined | tentative | needs-action */
        #[NotBlank]
        public ?FHIRParticipationStatusType $status = null,
        /** @var FHIRPeriod|null period Participation period of the actor */
        public ?FHIRPeriod $period = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
