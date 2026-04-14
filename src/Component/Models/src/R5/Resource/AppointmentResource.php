<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AppointmentStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\VirtualServiceDetail;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Appointment\AppointmentParticipant;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Appointment\AppointmentRecurrenceTemplate;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Appointment
 *
 * @description A booking of a healthcare event among patient(s), practitioner(s), related person(s) and/or device(s) for a specific date/time. This may result in one or more Encounter(s).
 */
#[FhirResource(type: 'Appointment', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Appointment', fhirVersion: 'R5')]
class AppointmentResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $implicitRules = null,
        /** @var AllLanguagesType|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AllLanguagesType $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier External Ids for this item */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var AppointmentStatusType|null status proposed | pending | booked | arrived | fulfilled | cancelled | noshow | entered-in-error | checked-in | waitlist */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?AppointmentStatusType $status = null,
        /** @var CodeableConcept|null cancellationReason The coded reason for the appointment being cancelled */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $cancellationReason = null,
        /** @var array<CodeableConcept> class Classification when becoming an encounter */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $class = [],
        /** @var array<CodeableConcept> serviceCategory A broad categorization of the service that is to be performed during this appointment */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $serviceCategory = [],
        /** @var array<CodeableReference> serviceType The specific service that is to be performed during this appointment */
        #[FhirProperty(
            fhirType: 'CodeableReference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference',
        )]
        public array $serviceType = [],
        /** @var array<CodeableConcept> specialty The specialty of a practitioner that would be required to perform the service requested in this appointment */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $specialty = [],
        /** @var CodeableConcept|null appointmentType The style of appointment or patient that has been booked in the slot (not service type) */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $appointmentType = null,
        /** @var array<CodeableReference> reason Reason this appointment is scheduled */
        #[FhirProperty(
            fhirType: 'CodeableReference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference',
        )]
        public array $reason = [],
        /** @var CodeableConcept|null priority Used to make informed decisions if needing to re-prioritize */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $priority = null,
        /** @var StringPrimitive|string|null description Shown on a subject line in a meeting request, or appointment list */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $description = null,
        /** @var array<Reference> replaces Appointment replaced by this Appointment */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $replaces = [],
        /** @var array<VirtualServiceDetail> virtualService Connection details of a virtual service (e.g. conference call) */
        #[FhirProperty(
            fhirType: 'VirtualServiceDetail',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\VirtualServiceDetail',
        )]
        public array $virtualService = [],
        /** @var array<Reference> supportingInformation Additional information to support the appointment */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $supportingInformation = [],
        /** @var Reference|null previousAppointment The previous appointment in a series */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $previousAppointment = null,
        /** @var Reference|null originatingAppointment The originating appointment in a recurring set of appointments */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $originatingAppointment = null,
        /** @var InstantPrimitive|null start When appointment is to take place */
        #[FhirProperty(fhirType: 'instant', propertyKind: 'primitive')]
        public ?InstantPrimitive $start = null,
        /** @var InstantPrimitive|null end When appointment is to conclude */
        #[FhirProperty(fhirType: 'instant', propertyKind: 'primitive')]
        public ?InstantPrimitive $end = null,
        /** @var PositiveIntPrimitive|null minutesDuration Can be less than start/end (e.g. estimate) */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $minutesDuration = null,
        /** @var array<Period> requestedPeriod Potential date/time interval(s) requested to allocate the appointment within */
        #[FhirProperty(
            fhirType: 'Period',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Period',
        )]
        public array $requestedPeriod = [],
        /** @var array<Reference> slot The slots that this appointment is filling */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $slot = [],
        /** @var array<Reference> account The set of accounts that may be used for billing for this Appointment */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $account = [],
        /** @var DateTimePrimitive|null created The date that this appointment was initially created */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $created = null,
        /** @var DateTimePrimitive|null cancellationDate When the appointment was cancelled */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $cancellationDate = null,
        /** @var array<Annotation> note Additional comments */
        #[FhirProperty(
            fhirType: 'Annotation',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Annotation',
        )]
        public array $note = [],
        /** @var array<CodeableReference> patientInstruction Detailed information and instructions for the patient */
        #[FhirProperty(
            fhirType: 'CodeableReference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference',
        )]
        public array $patientInstruction = [],
        /** @var array<Reference> basedOn The request this appointment is allocated to assess */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $basedOn = [],
        /** @var Reference|null subject The patient or group associated with the appointment */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $subject = null,
        /** @var array<AppointmentParticipant> participant Participants involved in appointment */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            isRequired: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Appointment\AppointmentParticipant',
        )]
        public array $participant = [],
        /** @var PositiveIntPrimitive|null recurrenceId The sequence number in the recurrence */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $recurrenceId = null,
        /** @var bool|null occurrenceChanged Indicates that this appointment varies from a recurrence pattern */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $occurrenceChanged = null,
        /** @var array<AppointmentRecurrenceTemplate> recurrenceTemplate Details of the recurrence pattern/template used to generate occurrences */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Appointment\AppointmentRecurrenceTemplate',
        )]
        public array $recurrenceTemplate = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
