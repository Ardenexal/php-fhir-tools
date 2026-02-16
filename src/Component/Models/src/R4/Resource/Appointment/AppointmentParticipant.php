<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Appointment;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ParticipantRequiredType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ParticipationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description List of participants involved in the appointment.
 */
#[FHIRBackboneElement(parentResource: 'Appointment', elementPath: 'Appointment.participant', fhirVersion: 'R4')]
class AppointmentParticipant extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<CodeableConcept> type Role of participant in the appointment */
        public array $type = [],
        /** @var Reference|null actor Person, Location/HealthcareService or Device */
        public ?Reference $actor = null,
        /** @var ParticipantRequiredType|null required required | optional | information-only */
        public ?ParticipantRequiredType $required = null,
        /** @var ParticipationStatusType|null status accepted | declined | tentative | needs-action */
        #[NotBlank]
        public ?ParticipationStatusType $status = null,
        /** @var Period|null period Participation period of the actor */
        public ?Period $period = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
