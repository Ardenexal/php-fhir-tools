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
class FHIRSearchParameter extends FHIRDomainResource
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri url Canonical identifier for this search parameter, represented as a URI (globally unique) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRUri $url = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string version Business version of the search parameter */
		public FHIRString|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string name Name for this search parameter (computer friendly) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCanonical derivedFrom Original definition for the search parameter */
		public ?FHIRCanonical $derivedFrom = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPublicationStatusType status draft | active | retired | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRPublicationStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean experimental For testing purposes, not real usage */
		public ?FHIRBoolean $experimental = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime date Date last changed */
		public ?FHIRDateTime $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string publisher Name of the publisher (organization or individual) */
		public FHIRString|string|null $publisher = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRContactDetail> contact Contact details for the publisher */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMarkdown description Natural language description of the search parameter */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUsageContext> useContext The context that the content is intended to support */
		public array $useContext = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> jurisdiction Intended jurisdiction for search parameter (if applicable) */
		public array $jurisdiction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMarkdown purpose Why this search parameter is defined */
		public ?FHIRMarkdown $purpose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode code Code used in URL */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRCode $code = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResourceTypeType> base The resource type(s) this search parameter applies to */
		public array $base = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSearchParamTypeType type number | date | string | token | reference | composite | quantity | uri | special */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRSearchParamTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string expression FHIRPath expression that extracts the values */
		public FHIRString|string|null $expression = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string xpath XPath that extracts the values */
		public FHIRString|string|null $xpath = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRXPathUsageTypeType xpathUsage normal | phonetic | nearby | distance | other */
		public ?FHIRXPathUsageTypeType $xpathUsage = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResourceTypeType> target Types of resource (if a resource reference) */
		public array $target = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean multipleOr Allow multiple values per parameter (or) */
		public ?FHIRBoolean $multipleOr = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean multipleAnd Allow multiple parameters (and) */
		public ?FHIRBoolean $multipleAnd = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSearchComparatorType> comparator eq | ne | gt | lt | ge | le | sa | eb | ap */
		public array $comparator = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSearchModifierCodeType> modifier missing | exact | contains | not | text | in | not-in | below | above | type | identifier | ofType */
		public array $modifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string> chain Chained names supported */
		public array $chain = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSearchParameterComponent> component For Composite resources to define the parts */
		public array $component = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
