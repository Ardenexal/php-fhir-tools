<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/Encounter
 * @description An interaction between a patient and healthcare provider(s) for the purpose of providing healthcare service(s) or assessing the health status of a patient.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Encounter', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Encounter', fhirVersion: 'R4')]
class EncounterResource extends DomainResourceResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ResourceResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Identifier(s) by which this encounter is known */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\EncounterStatusType status planned | arrived | triaged | in-progress | onleave | finished | cancelled + */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\EncounterStatusType $status = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Encounter\EncounterStatusHistory> statusHistory List of past encounter statuses */
		public array $statusHistory = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding class Classification of patient encounter */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding $class = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Encounter\EncounterClassHistory> classHistory List of past encounter classes */
		public array $classHistory = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> type Specific type of encounter */
		public array $type = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept serviceType Specific type of service */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $serviceType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept priority Indicates the urgency of the encounter */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $priority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference subject The patient or group present at the encounter */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $subject = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> episodeOfCare Episode(s) of care that this encounter should be recorded against */
		public array $episodeOfCare = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> basedOn The ServiceRequest that initiated this encounter */
		public array $basedOn = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Encounter\EncounterParticipant> participant List of participants involved in the encounter */
		public array $participant = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> appointment The appointment that scheduled this encounter */
		public array $appointment = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period period The start and end time of the encounter */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $period = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration length Quantity of time the encounter lasted (less time absent) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration $length = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> reasonCode Coded reason the encounter takes place */
		public array $reasonCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> reasonReference Reason the encounter takes place (reference) */
		public array $reasonReference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Encounter\EncounterDiagnosis> diagnosis The list of diagnosis relevant to this encounter */
		public array $diagnosis = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> account The set of accounts that may be used for billing for this Encounter */
		public array $account = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\Encounter\EncounterHospitalization hospitalization Details about the admission to a healthcare service */
		public ?Encounter\EncounterHospitalization $hospitalization = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Encounter\EncounterLocation> location List of locations where the patient has been */
		public array $location = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference serviceProvider The organization (facility) responsible for this encounter */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $serviceProvider = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference partOf Another Encounter this encounter is part of */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $partOf = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
