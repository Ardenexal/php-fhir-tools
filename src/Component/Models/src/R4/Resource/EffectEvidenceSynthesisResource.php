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
class EffectEvidenceSynthesisResource extends DomainResourceResource
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive url Canonical identifier for this effect evidence synthesis, represented as a URI (globally unique) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $url = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Additional identifier for the effect evidence synthesis */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string version Business version of the effect evidence synthesis */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string name Name for this effect evidence synthesis (computer friendly) */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string title Name for this effect evidence synthesis (human friendly) */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType status draft | active | retired | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive date Date last changed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string publisher Name of the publisher (organization or individual) */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $publisher = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail> contact Contact details for the publisher */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive description Natural language description of the effect evidence synthesis */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation> note Used for footnotes or explanatory notes */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext> useContext The context that the content is intended to support */
		public array $useContext = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> jurisdiction Intended jurisdiction for effect evidence synthesis (if applicable) */
		public array $jurisdiction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive copyright Use and/or publishing restrictions */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $copyright = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive approvalDate When the effect evidence synthesis was approved by publisher */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive $approvalDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive lastReviewDate When the effect evidence synthesis was last reviewed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive $lastReviewDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period effectivePeriod When the effect evidence synthesis is expected to be used */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $effectivePeriod = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> topic The category of the EffectEvidenceSynthesis, such as Education, Treatment, Assessment, etc. */
		public array $topic = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail> author Who authored the content */
		public array $author = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail> editor Who edited the content */
		public array $editor = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail> reviewer Who reviewed the content */
		public array $reviewer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail> endorser Who endorsed the content */
		public array $endorser = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\RelatedArtifact> relatedArtifact Additional documentation, citations, etc. */
		public array $relatedArtifact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept synthesisType Type of synthesis */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $synthesisType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept studyType Type of study */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $studyType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference population What population? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $population = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference exposure What exposure? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $exposure = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference exposureAlternative What comparison exposure? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $exposureAlternative = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference outcome What outcome? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $outcome = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\EffectEvidenceSynthesis\EffectEvidenceSynthesisSampleSize sampleSize What sample size was involved? */
		public ?EffectEvidenceSynthesis\EffectEvidenceSynthesisSampleSize $sampleSize = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\EffectEvidenceSynthesis\EffectEvidenceSynthesisResultsByExposure> resultsByExposure What was the result per exposure? */
		public array $resultsByExposure = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\EffectEvidenceSynthesis\EffectEvidenceSynthesisEffectEstimate> effectEstimate What was the estimated effect */
		public array $effectEstimate = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\EffectEvidenceSynthesis\EffectEvidenceSynthesisCertainty> certainty How certain is the effect */
		public array $certainty = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
