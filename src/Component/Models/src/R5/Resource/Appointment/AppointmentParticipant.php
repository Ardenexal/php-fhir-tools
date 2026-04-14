<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Appointment;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ParticipationStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description List of participants involved in the appointment.
 */
#[FHIRBackboneElement(parentResource: 'Appointment', elementPath: 'Appointment.participant', fhirVersion: 'R5')]
class AppointmentParticipant extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var array<CodeableConcept> type Role of participant in the appointment */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $type = [],
        /** @var Period|null period Participation period of the actor */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?Period $period = null,
        /** @var Reference|null actor The individual, device, location, or service participating in the appointment */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $actor = null,
        /** @var bool|null required The participant is required to attend (optional when false) */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $required = null,
        /** @var ParticipationStatusType|null status accepted | declined | tentative | needs-action */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?ParticipationStatusType $status = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
