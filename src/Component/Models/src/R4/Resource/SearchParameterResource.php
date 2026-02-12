<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 * @see http://hl7.org/fhir/StructureDefinition/SearchParameter
 * @description A search parameter that defines a named search item that can be used to search/filter on a resource.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'SearchParameter',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/SearchParameter',
	fhirVersion: 'R4',
)]
class SearchParameterResource extends DomainResourceResource
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive url Canonical identifier for this search parameter, represented as a URI (globally unique) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $url = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string version Business version of the search parameter */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string name Name for this search parameter (computer friendly) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive derivedFrom Original definition for the search parameter */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive $derivedFrom = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType status draft | active | retired | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType $status = null,
		/** @var null|bool experimental For testing purposes, not real usage */
		public ?bool $experimental = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive date Date last changed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string publisher Name of the publisher (organization or individual) */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $publisher = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail> contact Contact details for the publisher */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive description Natural language description of the search parameter */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext> useContext The context that the content is intended to support */
		public array $useContext = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> jurisdiction Intended jurisdiction for search parameter (if applicable) */
		public array $jurisdiction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive purpose Why this search parameter is defined */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $purpose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive code Code used in URL */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive $code = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ResourceTypeType> base The resource type(s) this search parameter applies to */
		public array $base = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\SearchParamTypeType type number | date | string | token | reference | composite | quantity | uri | special */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\SearchParamTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string expression FHIRPath expression that extracts the values */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $expression = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string xpath XPath that extracts the values */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $xpath = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\XPathUsageTypeType xpathUsage normal | phonetic | nearby | distance | other */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\XPathUsageTypeType $xpathUsage = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ResourceTypeType> target Types of resource (if a resource reference) */
		public array $target = [],
		/** @var null|bool multipleOr Allow multiple values per parameter (or) */
		public ?bool $multipleOr = null,
		/** @var null|bool multipleAnd Allow multiple parameters (and) */
		public ?bool $multipleAnd = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\SearchComparatorType> comparator eq | ne | gt | lt | ge | le | sa | eb | ap */
		public array $comparator = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\SearchModifierCodeType> modifier missing | exact | contains | not | text | in | not-in | below | above | type | identifier | ofType */
		public array $modifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string> chain Chained names supported */
		public array $chain = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\SearchParameter\SearchParameterComponent> component For Composite resources to define the parts */
		public array $component = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
