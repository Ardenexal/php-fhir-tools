<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/Encounter
 * @description An interaction between a patient and healthcare provider(s) for the purpose of providing healthcare service(s) or assessing the health status of a patient.  Encounter is primarily used to record information about the actual activities that occurred, where Appointment is used to record planned activities.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Encounter', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Encounter', fhirVersion: 'R5')]
class FHIREncounter extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Identifier(s) by which this encounter is known */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREncounterStatusType status planned | in-progress | on-hold | discharged | completed | cancelled | discontinued | entered-in-error | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIREncounterStatusType $status = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> class Classification of patient encounter context - e.g. Inpatient, outpatient */
		public array $class = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept priority Indicates the urgency of the encounter */
		public ?FHIRCodeableConcept $priority = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> type Specific type of encounter (e.g. e-mail consultation, surgical day-care, ...) */
		public array $type = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference> serviceType Specific type of service */
		public array $serviceType = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference subject The patient or group related to this encounter */
		public ?FHIRReference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept subjectStatus The current status of the subject in relation to the Encounter */
		public ?FHIRCodeableConcept $subjectStatus = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> episodeOfCare Episode(s) of care that this encounter should be recorded against */
		public array $episodeOfCare = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> basedOn The request that initiated this encounter */
		public array $basedOn = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> careTeam The group(s) that are allocated to participate in this encounter */
		public array $careTeam = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference partOf Another Encounter this encounter is part of */
		public ?FHIRReference $partOf = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference serviceProvider The organization (facility) responsible for this encounter */
		public ?FHIRReference $serviceProvider = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREncounterParticipant> participant List of participants involved in the encounter */
		public array $participant = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> appointment The appointment that scheduled this encounter */
		public array $appointment = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRVirtualServiceDetail> virtualService Connection details of a virtual service (e.g. conference call) */
		public array $virtualService = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod actualPeriod The actual start and end time of the encounter */
		public ?FHIRPeriod $actualPeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime plannedStartDate The planned start date/time (or admission date) of the encounter */
		public ?FHIRDateTime $plannedStartDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime plannedEndDate The planned end date/time (or discharge date) of the encounter */
		public ?FHIRDateTime $plannedEndDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration length Actual quantity of time the encounter lasted (less time absent) */
		public ?FHIRDuration $length = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREncounterReason> reason The list of medical reasons that are expected to be addressed during the episode of care */
		public array $reason = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREncounterDiagnosis> diagnosis The list of diagnosis relevant to this encounter */
		public array $diagnosis = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> account The set of accounts that may be used for billing for this Encounter */
		public array $account = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> dietPreference Diet preferences reported by the patient */
		public array $dietPreference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> specialArrangement Wheelchair, translator, stretcher, etc */
		public array $specialArrangement = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> specialCourtesy Special courtesies (VIP, board member) */
		public array $specialCourtesy = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREncounterAdmission admission Details about the admission to a healthcare service */
		public ?FHIREncounterAdmission $admission = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREncounterLocation> location List of locations where the patient has been */
		public array $location = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
