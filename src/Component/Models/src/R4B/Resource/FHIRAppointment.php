<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUnsignedInt;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Appointment
 *
 * @description A booking of a healthcare event among patient(s), practitioner(s), related person(s) and/or device(s) for a specific date/time. This may result in one or more Encounter(s).
 */
#[FhirResource(type: 'Appointment', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/Appointment', fhirVersion: 'R4B')]
class FHIRAppointment extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
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
        /** @var FHIRCodeableConcept|null cancelationReason The coded reason for the appointment being cancelled */
        public ?FHIRCodeableConcept $cancelationReason = null,
        /** @var array<FHIRCodeableConcept> serviceCategory A broad categorization of the service that is to be performed during this appointment */
        public array $serviceCategory = [],
        /** @var array<FHIRCodeableConcept> serviceType The specific service that is to be performed during this appointment */
        public array $serviceType = [],
        /** @var array<FHIRCodeableConcept> specialty The specialty of a practitioner that would be required to perform the service requested in this appointment */
        public array $specialty = [],
        /** @var FHIRCodeableConcept|null appointmentType The style of appointment or patient that has been booked in the slot (not service type) */
        public ?FHIRCodeableConcept $appointmentType = null,
        /** @var array<FHIRCodeableConcept> reasonCode Coded reason this appointment is scheduled */
        public array $reasonCode = [],
        /** @var array<FHIRReference> reasonReference Reason the appointment is to take place (resource) */
        public array $reasonReference = [],
        /** @var FHIRUnsignedInt|null priority Used to make informed decisions if needing to re-prioritize */
        public ?FHIRUnsignedInt $priority = null,
        /** @var FHIRString|string|null description Shown on a subject line in a meeting request, or appointment list */
        public FHIRString|string|null $description = null,
        /** @var array<FHIRReference> supportingInformation Additional information to support the appointment */
        public array $supportingInformation = [],
        /** @var FHIRInstant|null start When appointment is to take place */
        public ?FHIRInstant $start = null,
        /** @var FHIRInstant|null end When appointment is to conclude */
        public ?FHIRInstant $end = null,
        /** @var FHIRPositiveInt|null minutesDuration Can be less than start/end (e.g. estimate) */
        public ?FHIRPositiveInt $minutesDuration = null,
        /** @var array<FHIRReference> slot The slots that this appointment is filling */
        public array $slot = [],
        /** @var FHIRDateTime|null created The date that this appointment was initially created */
        public ?FHIRDateTime $created = null,
        /** @var FHIRString|string|null comment Additional comments */
        public FHIRString|string|null $comment = null,
        /** @var FHIRString|string|null patientInstruction Detailed information and instructions for the patient */
        public FHIRString|string|null $patientInstruction = null,
        /** @var array<FHIRReference> basedOn The service request this appointment is allocated to assess */
        public array $basedOn = [],
        /** @var array<FHIRAppointmentParticipant> participant Participants involved in appointment */
        public array $participant = [],
        /** @var array<FHIRPeriod> requestedPeriod Potential date/time interval(s) requested to allocate the appointment within */
        public array $requestedPeriod = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
