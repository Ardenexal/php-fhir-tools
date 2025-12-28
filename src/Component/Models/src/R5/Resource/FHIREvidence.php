<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 * @see http://hl7.org/fhir/StructureDefinition/Evidence
 * @description The Evidence Resource provides a machine-interpretable expression of an evidence concept including the evidence variables (e.g., population, exposures/interventions, comparators, outcomes, measured variables, confounding variables), the statistics, and the certainty of this evidence.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Evidence', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Evidence', fhirVersion: 'R5')]
class FHIREvidence extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType language Language of the resource content */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri url Canonical identifier for this evidence, represented as a globally unique URI */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $url = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier> identifier Additional identifier for the summary */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string version Business version of this summary */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding versionAlgorithmX How to compare versions */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding|null $versionAlgorithmX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string name Name for this summary (machine friendly) */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string title Name for this summary (human friendly) */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown citeAsX Citation for this evidence */
		public \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown|null $citeAsX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPublicationStatusType status draft | active | retired | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPublicationStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean experimental For testing purposes, not real usage */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $experimental = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime date Date last changed */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate approvalDate When the summary was approved by publisher */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate $approvalDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate lastReviewDate When the summary was last reviewed by the publisher */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate $lastReviewDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string publisher Name of the publisher/steward (organization or individual) */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $publisher = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactDetail> contact Contact details for the publisher */
		public array $contact = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactDetail> author Who authored the content */
		public array $author = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactDetail> editor Who edited the content */
		public array $editor = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactDetail> reviewer Who reviewed the content */
		public array $reviewer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactDetail> endorser Who endorsed the content */
		public array $endorser = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRUsageContext> useContext The context that the content is intended to support */
		public array $useContext = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown purpose Why this Evidence is defined */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $purpose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown copyright Use and/or publishing restrictions */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $copyright = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string copyrightLabel Copyright holder and year(s) */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $copyrightLabel = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRelatedArtifact> relatedArtifact Link or citation to artifact associated with the summary */
		public array $relatedArtifact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown description Description of the particular summary */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown assertion Declarative description of the Evidence */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $assertion = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation> note Footnotes and/or explanatory notes */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREvidenceVariableDefinition> variableDefinition Evidence variable such as population, exposure, or outcome */
		public array $variableDefinition = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept synthesisType The method to combine studies */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $synthesisType = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> studyDesign The design of the study that produced this evidence */
		public array $studyDesign = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREvidenceStatistic> statistic Values and parameters for a single statistic */
		public array $statistic = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREvidenceCertainty> certainty Certainty or quality of the evidence */
		public array $certainty = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
