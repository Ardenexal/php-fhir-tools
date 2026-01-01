<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 * @see http://hl7.org/fhir/StructureDefinition/EffectEvidenceSynthesis
 * @description The EffectEvidenceSynthesis resource describes the difference in an outcome between exposures states in a population where the effect estimate is derived from a combination of research studies.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'EffectEvidenceSynthesis',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/EffectEvidenceSynthesis',
	fhirVersion: 'R4',
)]
class FHIREffectEvidenceSynthesis extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri url Canonical identifier for this effect evidence synthesis, represented as a URI (globally unique) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri $url = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier> identifier Additional identifier for the effect evidence synthesis */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string version Business version of the effect evidence synthesis */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string name Name for this effect evidence synthesis (computer friendly) */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string title Name for this effect evidence synthesis (human friendly) */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPublicationStatusType status draft | active | retired | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPublicationStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime date Date last changed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string publisher Name of the publisher (organization or individual) */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $publisher = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRContactDetail> contact Contact details for the publisher */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRMarkdown description Natural language description of the effect evidence synthesis */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAnnotation> note Used for footnotes or explanatory notes */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRUsageContext> useContext The context that the content is intended to support */
		public array $useContext = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> jurisdiction Intended jurisdiction for effect evidence synthesis (if applicable) */
		public array $jurisdiction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRMarkdown copyright Use and/or publishing restrictions */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRMarkdown $copyright = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDate approvalDate When the effect evidence synthesis was approved by publisher */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDate $approvalDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDate lastReviewDate When the effect evidence synthesis was last reviewed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDate $lastReviewDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod effectivePeriod When the effect evidence synthesis is expected to be used */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod $effectivePeriod = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> topic The category of the EffectEvidenceSynthesis, such as Education, Treatment, Assessment, etc. */
		public array $topic = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRContactDetail> author Who authored the content */
		public array $author = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRContactDetail> editor Who edited the content */
		public array $editor = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRContactDetail> reviewer Who reviewed the content */
		public array $reviewer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRContactDetail> endorser Who endorsed the content */
		public array $endorser = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRelatedArtifact> relatedArtifact Additional documentation, citations, etc. */
		public array $relatedArtifact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept synthesisType Type of synthesis */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $synthesisType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept studyType Type of study */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $studyType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference population What population? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $population = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference exposure What exposure? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $exposure = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference exposureAlternative What comparison exposure? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $exposureAlternative = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference outcome What outcome? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $outcome = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIREffectEvidenceSynthesisSampleSize sampleSize What sample size was involved? */
		public ?FHIREffectEvidenceSynthesisSampleSize $sampleSize = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIREffectEvidenceSynthesisResultsByExposure> resultsByExposure What was the result per exposure? */
		public array $resultsByExposure = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIREffectEvidenceSynthesisEffectEstimate> effectEstimate What was the estimated effect */
		public array $effectEstimate = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIREffectEvidenceSynthesisCertainty> certainty How certain is the effect */
		public array $certainty = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
