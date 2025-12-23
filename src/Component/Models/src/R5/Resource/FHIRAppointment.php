<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/Appointment
 * @description A booking of a healthcare event among patient(s), practitioner(s), related person(s) and/or device(s) for a specific date/time. This may result in one or more Encounter(s).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Appointment', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Appointment', fhirVersion: 'R5')]
class FHIRAppointment extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAllLanguagesType language Language of the resource content */
		public ?FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier External Ids for this item */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAppointmentStatusType status proposed | pending | booked | arrived | fulfilled | cancelled | noshow | entered-in-error | checked-in | waitlist */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRAppointmentStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept cancellationReason The coded reason for the appointment being cancelled */
		public ?FHIRCodeableConcept $cancellationReason = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> class Classification when becoming an encounter */
		public array $class = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> serviceCategory A broad categorization of the service that is to be performed during this appointment */
		public array $serviceCategory = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference> serviceType The specific service that is to be performed during this appointment */
		public array $serviceType = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> specialty The specialty of a practitioner that would be required to perform the service requested in this appointment */
		public array $specialty = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept appointmentType The style of appointment or patient that has been booked in the slot (not service type) */
		public ?FHIRCodeableConcept $appointmentType = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference> reason Reason this appointment is scheduled */
		public array $reason = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept priority Used to make informed decisions if needing to re-prioritize */
		public ?FHIRCodeableConcept $priority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string description Shown on a subject line in a meeting request, or appointment list */
		public FHIRString|string|null $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> replaces Appointment replaced by this Appointment */
		public array $replaces = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRVirtualServiceDetail> virtualService Connection details of a virtual service (e.g. conference call) */
		public array $virtualService = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> supportingInformation Additional information to support the appointment */
		public array $supportingInformation = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference previousAppointment The previous appointment in a series */
		public ?FHIRReference $previousAppointment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference originatingAppointment The originating appointment in a recurring set of appointments */
		public ?FHIRReference $originatingAppointment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInstant start When appointment is to take place */
		public ?FHIRInstant $start = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInstant end When appointment is to conclude */
		public ?FHIRInstant $end = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt minutesDuration Can be less than start/end (e.g. estimate) */
		public ?FHIRPositiveInt $minutesDuration = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod> requestedPeriod Potential date/time interval(s) requested to allocate the appointment within */
		public array $requestedPeriod = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> slot The slots that this appointment is filling */
		public array $slot = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> account The set of accounts that may be used for billing for this Appointment */
		public array $account = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime created The date that this appointment was initially created */
		public ?FHIRDateTime $created = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime cancellationDate When the appointment was cancelled */
		public ?FHIRDateTime $cancellationDate = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAnnotation> note Additional comments */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference> patientInstruction Detailed information and instructions for the patient */
		public array $patientInstruction = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> basedOn The request this appointment is allocated to assess */
		public array $basedOn = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference subject The patient or group associated with the appointment */
		public ?FHIRReference $subject = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAppointmentParticipant> participant Participants involved in appointment */
		public array $participant = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt recurrenceId The sequence number in the recurrence */
		public ?FHIRPositiveInt $recurrenceId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean occurrenceChanged Indicates that this appointment varies from a recurrence pattern */
		public ?FHIRBoolean $occurrenceChanged = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAppointmentRecurrenceTemplate> recurrenceTemplate Details of the recurrence pattern/template used to generate occurrences */
		public array $recurrenceTemplate = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
