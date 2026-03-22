<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 * @see http://hl7.org/fhir/StructureDefinition/Evidence
 * @description The Evidence resource describes the conditional state (population and any exposures being compared within the population) and outcome (if specified) that the knowledge (evidence, assertion, recommendation) is about.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource(type: 'Evidence', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Evidence', fhirVersion: 'R4')]
class EvidenceResource extends DomainResourceResource
{
	public const FHIR_PROPERTY_MAP = [
		'id' => [
			'fhirType' => 'http://hl7.org/fhirpath/System.String',
			'propertyKind' => 'scalar',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'meta' => [
			'fhirType' => 'Meta',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'implicitRules' => [
			'fhirType' => 'uri',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'language' => [
			'fhirType' => 'code',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'text' => [
			'fhirType' => 'Narrative',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'contained' => [
			'fhirType' => 'Resource',
			'propertyKind' => 'resource',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'extension' => [
			'fhirType' => 'Extension',
			'propertyKind' => 'extension',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'modifierExtension' => [
			'fhirType' => 'Extension',
			'propertyKind' => 'modifierExtension',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'url' => [
			'fhirType' => 'uri',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'identifier' => [
			'fhirType' => 'Identifier',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier',
			'variants' => null,
		],
		'version' => [
			'fhirType' => 'string',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'name' => [
			'fhirType' => 'string',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'title' => [
			'fhirType' => 'string',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'shortTitle' => [
			'fhirType' => 'string',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'subtitle' => [
			'fhirType' => 'string',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'status' => [
			'fhirType' => 'code',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => true,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'date' => [
			'fhirType' => 'dateTime',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'publisher' => [
			'fhirType' => 'string',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'contact' => [
			'fhirType' => 'ContactDetail',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail',
			'variants' => null,
		],
		'description' => [
			'fhirType' => 'markdown',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'note' => [
			'fhirType' => 'Annotation',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation',
			'variants' => null,
		],
		'useContext' => [
			'fhirType' => 'UsageContext',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext',
			'variants' => null,
		],
		'jurisdiction' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
			'variants' => null,
		],
		'copyright' => [
			'fhirType' => 'markdown',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'approvalDate' => [
			'fhirType' => 'date',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'lastReviewDate' => [
			'fhirType' => 'date',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'effectivePeriod' => [
			'fhirType' => 'Period',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'topic' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
			'variants' => null,
		],
		'author' => [
			'fhirType' => 'ContactDetail',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail',
			'variants' => null,
		],
		'editor' => [
			'fhirType' => 'ContactDetail',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail',
			'variants' => null,
		],
		'reviewer' => [
			'fhirType' => 'ContactDetail',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail',
			'variants' => null,
		],
		'endorser' => [
			'fhirType' => 'ContactDetail',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail',
			'variants' => null,
		],
		'relatedArtifact' => [
			'fhirType' => 'RelatedArtifact',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\RelatedArtifact',
			'variants' => null,
		],
		'exposureBackground' => [
			'fhirType' => 'Reference',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => true,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'exposureVariant' => [
			'fhirType' => 'Reference',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
			'variants' => null,
		],
		'outcome' => [
			'fhirType' => 'Reference',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
			'variants' => null,
		],
	];

	public function __construct(
		/** @var null|string id Logical id of this artifact */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta meta Metadata about the resource */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive implicitRules A set of rules under which this content was created */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $implicitRules = null,
		/** @var null|string language Language of the resource content */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative text Text summary of the resource, for human interpretation */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ResourceResource> contained Contained, inline Resources */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive url Canonical identifier for this evidence, represented as a URI (globally unique) */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $url = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Additional identifier for the evidence */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
			fhirType: 'Identifier',
			propertyKind: 'complex',
			isArray: true,
			phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier',
		)]
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string version Business version of the evidence */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string name Name for this evidence (computer friendly) */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string title Name for this evidence (human friendly) */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string shortTitle Title for use in informal contexts */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $shortTitle = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string subtitle Subordinate title of the Evidence */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $subtitle = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType status draft | active | retired | unknown */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), \Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive date Date last changed */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string publisher Name of the publisher (organization or individual) */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $publisher = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail> contact Contact details for the publisher */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
			fhirType: 'ContactDetail',
			propertyKind: 'complex',
			isArray: true,
			phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail',
		)]
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive description Natural language description of the evidence */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation> note Used for footnotes or explanatory notes */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
			fhirType: 'Annotation',
			propertyKind: 'complex',
			isArray: true,
			phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation',
		)]
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext> useContext The context that the content is intended to support */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
			fhirType: 'UsageContext',
			propertyKind: 'complex',
			isArray: true,
			phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext',
		)]
		public array $useContext = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> jurisdiction Intended jurisdiction for evidence (if applicable) */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
			fhirType: 'CodeableConcept',
			propertyKind: 'complex',
			isArray: true,
			phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
		)]
		public array $jurisdiction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive copyright Use and/or publishing restrictions */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $copyright = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive approvalDate When the evidence was approved by publisher */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'date', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive $approvalDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive lastReviewDate When the evidence was last reviewed */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'date', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive $lastReviewDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period effectivePeriod When the evidence is expected to be used */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $effectivePeriod = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> topic The category of the Evidence, such as Education, Treatment, Assessment, etc. */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
			fhirType: 'CodeableConcept',
			propertyKind: 'complex',
			isArray: true,
			phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
		)]
		public array $topic = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail> author Who authored the content */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
			fhirType: 'ContactDetail',
			propertyKind: 'complex',
			isArray: true,
			phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail',
		)]
		public array $author = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail> editor Who edited the content */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
			fhirType: 'ContactDetail',
			propertyKind: 'complex',
			isArray: true,
			phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail',
		)]
		public array $editor = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail> reviewer Who reviewed the content */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
			fhirType: 'ContactDetail',
			propertyKind: 'complex',
			isArray: true,
			phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail',
		)]
		public array $reviewer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail> endorser Who endorsed the content */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
			fhirType: 'ContactDetail',
			propertyKind: 'complex',
			isArray: true,
			phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail',
		)]
		public array $endorser = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\RelatedArtifact> relatedArtifact Additional documentation, citations, etc. */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
			fhirType: 'RelatedArtifact',
			propertyKind: 'complex',
			isArray: true,
			phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\RelatedArtifact',
		)]
		public array $relatedArtifact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference exposureBackground What population? */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isRequired: true), \Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $exposureBackground = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> exposureVariant What exposure? */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
			fhirType: 'Reference',
			propertyKind: 'complex',
			isArray: true,
			phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
		)]
		public array $exposureVariant = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> outcome What outcome? */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
			fhirType: 'Reference',
			propertyKind: 'complex',
			isArray: true,
			phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
		)]
		public array $outcome = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
