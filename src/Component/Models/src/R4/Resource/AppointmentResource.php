<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\AppointmentStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Appointment\AppointmentParticipant;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Appointment
 *
 * @description A booking of a healthcare event among patient(s), practitioner(s), related person(s) and/or device(s) for a specific date/time. This may result in one or more Encounter(s).
 */
#[FhirResource(type: 'Appointment', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Appointment', fhirVersion: 'R4')]
class AppointmentResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier External Ids for this item */
        public array $identifier = [],
        /** @var AppointmentStatusType|null status proposed | pending | booked | arrived | fulfilled | cancelled | noshow | entered-in-error | checked-in | waitlist */
        #[NotBlank]
        public ?AppointmentStatusType $status = null,
        /** @var CodeableConcept|null cancelationReason The coded reason for the appointment being cancelled */
        public ?CodeableConcept $cancelationReason = null,
        /** @var array<CodeableConcept> serviceCategory A broad categorization of the service that is to be performed during this appointment */
        public array $serviceCategory = [],
        /** @var array<CodeableConcept> serviceType The specific service that is to be performed during this appointment */
        public array $serviceType = [],
        /** @var array<CodeableConcept> specialty The specialty of a practitioner that would be required to perform the service requested in this appointment */
        public array $specialty = [],
        /** @var CodeableConcept|null appointmentType The style of appointment or patient that has been booked in the slot (not service type) */
        public ?CodeableConcept $appointmentType = null,
        /** @var array<CodeableConcept> reasonCode Coded reason this appointment is scheduled */
        public array $reasonCode = [],
        /** @var array<Reference> reasonReference Reason the appointment is to take place (resource) */
        public array $reasonReference = [],
        /** @var UnsignedIntPrimitive|null priority Used to make informed decisions if needing to re-prioritize */
        public ?UnsignedIntPrimitive $priority = null,
        /** @var StringPrimitive|string|null description Shown on a subject line in a meeting request, or appointment list */
        public StringPrimitive|string|null $description = null,
        /** @var array<Reference> supportingInformation Additional information to support the appointment */
        public array $supportingInformation = [],
        /** @var InstantPrimitive|null start When appointment is to take place */
        public ?InstantPrimitive $start = null,
        /** @var InstantPrimitive|null end When appointment is to conclude */
        public ?InstantPrimitive $end = null,
        /** @var PositiveIntPrimitive|null minutesDuration Can be less than start/end (e.g. estimate) */
        public ?PositiveIntPrimitive $minutesDuration = null,
        /** @var array<Reference> slot The slots that this appointment is filling */
        public array $slot = [],
        /** @var DateTimePrimitive|null created The date that this appointment was initially created */
        public ?DateTimePrimitive $created = null,
        /** @var StringPrimitive|string|null comment Additional comments */
        public StringPrimitive|string|null $comment = null,
        /** @var StringPrimitive|string|null patientInstruction Detailed information and instructions for the patient */
        public StringPrimitive|string|null $patientInstruction = null,
        /** @var array<Reference> basedOn The service request this appointment is allocated to assess */
        public array $basedOn = [],
        /** @var array<AppointmentParticipant> participant Participants involved in appointment */
        public array $participant = [],
        /** @var array<Period> requestedPeriod Potential date/time interval(s) requested to allocate the appointment within */
        public array $requestedPeriod = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
