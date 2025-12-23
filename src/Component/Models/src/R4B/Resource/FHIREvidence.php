<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 * @see http://hl7.org/fhir/StructureDefinition/Evidence
 * @description The Evidence Resource provides a machine-interpretable expression of an evidence concept including the evidence variables (eg population, exposures/interventions, comparators, outcomes, measured variables, confounding variables), the statistics, and the certainty of this evidence.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Evidence', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/Evidence', fhirVersion: 'R4B')]
class FHIREvidence extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri url Canonical identifier for this evidence, represented as a globally unique URI */
		public ?FHIRUri $url = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier> identifier Additional identifier for the summary */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string version Business version of this summary */
		public FHIRString|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string title Name for this summary (human friendly) */
		public FHIRString|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown citeAsX Citation for this evidence */
		public FHIRReference|FHIRMarkdown|null $citeAsX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPublicationStatusType status draft | active | retired | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRPublicationStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime date Date last changed */
		public ?FHIRDateTime $date = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUsageContext> useContext The context that the content is intended to support */
		public array $useContext = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDate approvalDate When the summary was approved by publisher */
		public ?FHIRDate $approvalDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDate lastReviewDate When the summary was last reviewed */
		public ?FHIRDate $lastReviewDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string publisher Name of the publisher (organization or individual) */
		public FHIRString|string|null $publisher = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRContactDetail> contact Contact details for the publisher */
		public array $contact = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRContactDetail> author Who authored the content */
		public array $author = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRContactDetail> editor Who edited the content */
		public array $editor = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRContactDetail> reviewer Who reviewed the content */
		public array $reviewer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRContactDetail> endorser Who endorsed the content */
		public array $endorser = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRelatedArtifact> relatedArtifact Link or citation to artifact associated with the summary */
		public array $relatedArtifact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown description Description of the particular summary */
		public ?FHIRMarkdown $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown assertion Declarative description of the Evidence */
		public ?FHIRMarkdown $assertion = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAnnotation> note Footnotes and/or explanatory notes */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIREvidenceVariableDefinition> variableDefinition Evidence variable such as population, exposure, or outcome */
		public array $variableDefinition = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept synthesisType The method to combine studies */
		public ?FHIRCodeableConcept $synthesisType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept studyType The type of study that produced this evidence */
		public ?FHIRCodeableConcept $studyType = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIREvidenceStatistic> statistic Values and parameters for a single statistic */
		public array $statistic = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIREvidenceCertainty> certainty Certainty or quality of the evidence */
		public array $certainty = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
