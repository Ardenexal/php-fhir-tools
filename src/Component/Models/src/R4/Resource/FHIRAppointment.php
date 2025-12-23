<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/Appointment
 * @description A booking of a healthcare event among patient(s), practitioner(s), related person(s) and/or device(s) for a specific date/time. This may result in one or more Encounter(s).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Appointment', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Appointment', fhirVersion: 'R4')]
class FHIRAppointment extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier> identifier External Ids for this item */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAppointmentStatusType status proposed | pending | booked | arrived | fulfilled | cancelled | noshow | entered-in-error | checked-in | waitlist */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRAppointmentStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept cancelationReason The coded reason for the appointment being cancelled */
		public ?FHIRCodeableConcept $cancelationReason = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> serviceCategory A broad categorization of the service that is to be performed during this appointment */
		public array $serviceCategory = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> serviceType The specific service that is to be performed during this appointment */
		public array $serviceType = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> specialty The specialty of a practitioner that would be required to perform the service requested in this appointment */
		public array $specialty = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept appointmentType The style of appointment or patient that has been booked in the slot (not service type) */
		public ?FHIRCodeableConcept $appointmentType = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> reasonCode Coded reason this appointment is scheduled */
		public array $reasonCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> reasonReference Reason the appointment is to take place (resource) */
		public array $reasonReference = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUnsignedInt priority Used to make informed decisions if needing to re-prioritize */
		public ?FHIRUnsignedInt $priority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string description Shown on a subject line in a meeting request, or appointment list */
		public FHIRString|string|null $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> supportingInformation Additional information to support the appointment */
		public array $supportingInformation = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInstant start When appointment is to take place */
		public ?FHIRInstant $start = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInstant end When appointment is to conclude */
		public ?FHIRInstant $end = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPositiveInt minutesDuration Can be less than start/end (e.g. estimate) */
		public ?FHIRPositiveInt $minutesDuration = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> slot The slots that this appointment is filling */
		public array $slot = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime created The date that this appointment was initially created */
		public ?FHIRDateTime $created = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string comment Additional comments */
		public FHIRString|string|null $comment = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string patientInstruction Detailed information and instructions for the patient */
		public FHIRString|string|null $patientInstruction = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> basedOn The service request this appointment is allocated to assess */
		public array $basedOn = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAppointmentParticipant> participant Participants involved in appointment */
		public array $participant = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod> requestedPeriod Potential date/time interval(s) requested to allocate the appointment within */
		public array $requestedPeriod = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
