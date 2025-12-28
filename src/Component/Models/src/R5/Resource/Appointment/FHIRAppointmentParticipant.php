<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description List of participants involved in the appointment.
 */
#[FHIRBackboneElement(parentResource: 'Appointment', elementPath: 'Appointment.participant', fhirVersion: 'R5')]
class FHIRAppointmentParticipant extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
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
        /** @var FHIRPeriod|null period Participation period of the actor */
        public ?\FHIRPeriod $period = null,
        /** @var FHIRReference|null actor The individual, device, location, or service participating in the appointment */
        public ?\FHIRReference $actor = null,
        /** @var FHIRBoolean|null required The participant is required to attend (optional when false) */
        public ?\FHIRBoolean $required = null,
        /** @var FHIRParticipationStatusType|null status accepted | declined | tentative | needs-action */
        #[NotBlank]
        public ?\FHIRParticipationStatusType $status = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
