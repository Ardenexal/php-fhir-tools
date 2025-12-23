<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/Encounter
 * @description An interaction between a patient and healthcare provider(s) for the purpose of providing healthcare service(s) or assessing the health status of a patient.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Encounter', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Encounter', fhirVersion: 'R4')]
class FHIREncounter extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier> identifier Identifier(s) by which this encounter is known */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIREncounterStatusType status planned | arrived | triaged | in-progress | onleave | finished | cancelled + */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIREncounterStatusType $status = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIREncounterStatusHistory> statusHistory List of past encounter statuses */
		public array $statusHistory = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCoding class Classification of patient encounter */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRCoding $class = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIREncounterClassHistory> classHistory List of past encounter classes */
		public array $classHistory = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> type Specific type of encounter */
		public array $type = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept serviceType Specific type of service */
		public ?FHIRCodeableConcept $serviceType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept priority Indicates the urgency of the encounter */
		public ?FHIRCodeableConcept $priority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference subject The patient or group present at the encounter */
		public ?FHIRReference $subject = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> episodeOfCare Episode(s) of care that this encounter should be recorded against */
		public array $episodeOfCare = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> basedOn The ServiceRequest that initiated this encounter */
		public array $basedOn = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIREncounterParticipant> participant List of participants involved in the encounter */
		public array $participant = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> appointment The appointment that scheduled this encounter */
		public array $appointment = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod period The start and end time of the encounter */
		public ?FHIRPeriod $period = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDuration length Quantity of time the encounter lasted (less time absent) */
		public ?FHIRDuration $length = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> reasonCode Coded reason the encounter takes place */
		public array $reasonCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> reasonReference Reason the encounter takes place (reference) */
		public array $reasonReference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIREncounterDiagnosis> diagnosis The list of diagnosis relevant to this encounter */
		public array $diagnosis = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> account The set of accounts that may be used for billing for this Encounter */
		public array $account = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIREncounterHospitalization hospitalization Details about the admission to a healthcare service */
		public ?FHIREncounterHospitalization $hospitalization = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIREncounterLocation> location List of locations where the patient has been */
		public array $location = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference serviceProvider The organization (facility) responsible for this encounter */
		public ?FHIRReference $serviceProvider = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference partOf Another Encounter this encounter is part of */
		public ?FHIRReference $partOf = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
