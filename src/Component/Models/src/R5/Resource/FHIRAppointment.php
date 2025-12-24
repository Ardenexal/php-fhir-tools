<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRVirtualServiceDetail;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Appointment
 *
 * @description A booking of a healthcare event among patient(s), practitioner(s), related person(s) and/or device(s) for a specific date/time. This may result in one or more Encounter(s).
 */
#[FhirResource(type: 'Appointment', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Appointment', fhirVersion: 'R5')]
class FHIRAppointment extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier External Ids for this item */
        public array $identifier = [],
        /** @var FHIRAppointmentStatusType|null status proposed | pending | booked | arrived | fulfilled | cancelled | noshow | entered-in-error | checked-in | waitlist */
        #[NotBlank]
        public ?FHIRAppointmentStatusType $status = null,
        /** @var FHIRCodeableConcept|null cancellationReason The coded reason for the appointment being cancelled */
        public ?FHIRCodeableConcept $cancellationReason = null,
        /** @var array<FHIRCodeableConcept> class Classification when becoming an encounter */
        public array $class = [],
        /** @var array<FHIRCodeableConcept> serviceCategory A broad categorization of the service that is to be performed during this appointment */
        public array $serviceCategory = [],
        /** @var array<FHIRCodeableReference> serviceType The specific service that is to be performed during this appointment */
        public array $serviceType = [],
        /** @var array<FHIRCodeableConcept> specialty The specialty of a practitioner that would be required to perform the service requested in this appointment */
        public array $specialty = [],
        /** @var FHIRCodeableConcept|null appointmentType The style of appointment or patient that has been booked in the slot (not service type) */
        public ?FHIRCodeableConcept $appointmentType = null,
        /** @var array<FHIRCodeableReference> reason Reason this appointment is scheduled */
        public array $reason = [],
        /** @var FHIRCodeableConcept|null priority Used to make informed decisions if needing to re-prioritize */
        public ?FHIRCodeableConcept $priority = null,
        /** @var FHIRString|string|null description Shown on a subject line in a meeting request, or appointment list */
        public FHIRString|string|null $description = null,
        /** @var array<FHIRReference> replaces Appointment replaced by this Appointment */
        public array $replaces = [],
        /** @var array<FHIRVirtualServiceDetail> virtualService Connection details of a virtual service (e.g. conference call) */
        public array $virtualService = [],
        /** @var array<FHIRReference> supportingInformation Additional information to support the appointment */
        public array $supportingInformation = [],
        /** @var FHIRReference|null previousAppointment The previous appointment in a series */
        public ?FHIRReference $previousAppointment = null,
        /** @var FHIRReference|null originatingAppointment The originating appointment in a recurring set of appointments */
        public ?FHIRReference $originatingAppointment = null,
        /** @var FHIRInstant|null start When appointment is to take place */
        public ?FHIRInstant $start = null,
        /** @var FHIRInstant|null end When appointment is to conclude */
        public ?FHIRInstant $end = null,
        /** @var FHIRPositiveInt|null minutesDuration Can be less than start/end (e.g. estimate) */
        public ?FHIRPositiveInt $minutesDuration = null,
        /** @var array<FHIRPeriod> requestedPeriod Potential date/time interval(s) requested to allocate the appointment within */
        public array $requestedPeriod = [],
        /** @var array<FHIRReference> slot The slots that this appointment is filling */
        public array $slot = [],
        /** @var array<FHIRReference> account The set of accounts that may be used for billing for this Appointment */
        public array $account = [],
        /** @var FHIRDateTime|null created The date that this appointment was initially created */
        public ?FHIRDateTime $created = null,
        /** @var FHIRDateTime|null cancellationDate When the appointment was cancelled */
        public ?FHIRDateTime $cancellationDate = null,
        /** @var array<FHIRAnnotation> note Additional comments */
        public array $note = [],
        /** @var array<FHIRCodeableReference> patientInstruction Detailed information and instructions for the patient */
        public array $patientInstruction = [],
        /** @var array<FHIRReference> basedOn The request this appointment is allocated to assess */
        public array $basedOn = [],
        /** @var FHIRReference|null subject The patient or group associated with the appointment */
        public ?FHIRReference $subject = null,
        /** @var array<FHIRAppointmentParticipant> participant Participants involved in appointment */
        public array $participant = [],
        /** @var FHIRPositiveInt|null recurrenceId The sequence number in the recurrence */
        public ?FHIRPositiveInt $recurrenceId = null,
        /** @var FHIRBoolean|null occurrenceChanged Indicates that this appointment varies from a recurrence pattern */
        public ?FHIRBoolean $occurrenceChanged = null,
        /** @var array<FHIRAppointmentRecurrenceTemplate> recurrenceTemplate Details of the recurrence pattern/template used to generate occurrences */
        public array $recurrenceTemplate = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
