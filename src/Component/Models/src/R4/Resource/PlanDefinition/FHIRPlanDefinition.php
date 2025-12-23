<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 * @see http://hl7.org/fhir/StructureDefinition/PlanDefinition
 * @description This resource allows for the definition of various types of plans as a sharable, consumable, and executable artifact. The resource is general enough to support the description of a broad range of clinical artifacts such as clinical decision support rules, order sets and protocols.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'PlanDefinition',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/PlanDefinition',
	fhirVersion: 'R4',
)]
class FHIRPlanDefinition extends FHIRDomainResource
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri url Canonical identifier for this plan definition, represented as a URI (globally unique) */
		public ?FHIRUri $url = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier> identifier Additional identifier for the plan definition */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string version Business version of the plan definition */
		public FHIRString|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string name Name for this plan definition (computer friendly) */
		public FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string title Name for this plan definition (human friendly) */
		public FHIRString|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string subtitle Subordinate title of the plan definition */
		public FHIRString|string|null $subtitle = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept type order-set | clinical-protocol | eca-rule | workflow-definition */
		public ?FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPublicationStatusType status draft | active | retired | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRPublicationStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean experimental For testing purposes, not real usage */
		public ?FHIRBoolean $experimental = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference subjectX Type of individual the plan definition is focused on */
		public FHIRCodeableConcept|FHIRReference|null $subjectX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime date Date last changed */
		public ?FHIRDateTime $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string publisher Name of the publisher (organization or individual) */
		public FHIRString|string|null $publisher = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRContactDetail> contact Contact details for the publisher */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMarkdown description Natural language description of the plan definition */
		public ?FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUsageContext> useContext The context that the content is intended to support */
		public array $useContext = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> jurisdiction Intended jurisdiction for plan definition (if applicable) */
		public array $jurisdiction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMarkdown purpose Why this plan definition is defined */
		public ?FHIRMarkdown $purpose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string usage Describes the clinical usage of the plan */
		public FHIRString|string|null $usage = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMarkdown copyright Use and/or publishing restrictions */
		public ?FHIRMarkdown $copyright = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDate approvalDate When the plan definition was approved by publisher */
		public ?FHIRDate $approvalDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDate lastReviewDate When the plan definition was last reviewed */
		public ?FHIRDate $lastReviewDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod effectivePeriod When the plan definition is expected to be used */
		public ?FHIRPeriod $effectivePeriod = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> topic E.g. Education, Treatment, Assessment */
		public array $topic = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRContactDetail> author Who authored the content */
		public array $author = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRContactDetail> editor Who edited the content */
		public array $editor = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRContactDetail> reviewer Who reviewed the content */
		public array $reviewer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRContactDetail> endorser Who endorsed the content */
		public array $endorser = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRelatedArtifact> relatedArtifact Additional documentation, citations */
		public array $relatedArtifact = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCanonical> library Logic used by the plan definition */
		public array $library = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPlanDefinitionGoal> goal What the plan is trying to accomplish */
		public array $goal = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPlanDefinitionAction> action Action defined by the plan */
		public array $action = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
