<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 * @see http://hl7.org/fhir/StructureDefinition/ResearchStudy
 * @description A process where a researcher or organization plans and then executes a series of steps intended to increase the field of healthcare-related knowledge.  This includes studies of safety, efficacy, comparative effectiveness and other information about medications, devices, therapies and other interventional and investigative techniques.  A ResearchStudy involves the gathering of information about human or animal subjects.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'ResearchStudy', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/ResearchStudy', fhirVersion: 'R4')]
class ResearchStudyResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Business Identifier for study */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string title Name for this study */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $title = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> protocol Steps followed in executing study */
		public array $protocol = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> partOf Part of larger study */
		public array $partOf = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ResearchStudyStatusType status active | administratively-completed | approved | closed-to-accrual | closed-to-accrual-and-intervention | completed | disapproved | in-review | temporarily-closed-to-accrual | temporarily-closed-to-accrual-and-intervention | withdrawn */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ResearchStudyStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept primaryPurposeType treatment | prevention | diagnostic | supportive-care | screening | health-services-research | basic-science | device-feasibility */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $primaryPurposeType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept phase n-a | early-phase-1 | phase-1 | phase-1-phase-2 | phase-2 | phase-2-phase-3 | phase-3 | phase-4 */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $phase = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> category Classifications for the study */
		public array $category = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> focus Drugs, devices, etc. under study */
		public array $focus = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> condition Condition being studied */
		public array $condition = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail> contact Contact details for the study */
		public array $contact = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\RelatedArtifact> relatedArtifact References and dependencies */
		public array $relatedArtifact = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> keyword Used to search for the study */
		public array $keyword = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> location Geographic region(s) for study */
		public array $location = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive description What this is study doing */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> enrollment Inclusion & exclusion criteria */
		public array $enrollment = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period period When the study began and ended */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $period = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference sponsor Organization that initiates and is legally responsible for the study */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $sponsor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference principalInvestigator Researcher who oversees multiple aspects of the study */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $principalInvestigator = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> site Facility where study activities are conducted */
		public array $site = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept reasonStopped accrual-goal-met | closed-due-to-toxicity | closed-due-to-lack-of-study-progress | temporarily-closed-per-study-design */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $reasonStopped = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation> note Comments made about the study */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ResearchStudy\ResearchStudyArm> arm Defined path through the study for a subject */
		public array $arm = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ResearchStudy\ResearchStudyObjective> objective A goal for the study */
		public array $objective = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
