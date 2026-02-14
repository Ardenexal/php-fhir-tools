<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ResourceTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\SearchComparatorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\SearchModifierCodeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\SearchParamTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\XPathUsageTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\SearchParameter\SearchParameterComponent;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/SearchParameter
 *
 * @description A search parameter that defines a named search item that can be used to search/filter on a resource.
 */
#[FhirResource(
    type: 'SearchParameter',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/SearchParameter',
    fhirVersion: 'R4',
)]
class SearchParameterResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var UriPrimitive|null url Canonical identifier for this search parameter, represented as a URI (globally unique) */
        #[NotBlank]
        public ?UriPrimitive $url = null,
        /** @var StringPrimitive|string|null version Business version of the search parameter */
        public StringPrimitive|string|null $version = null,
        /** @var StringPrimitive|string|null name Name for this search parameter (computer friendly) */
        #[NotBlank]
        public StringPrimitive|string|null $name = null,
        /** @var CanonicalPrimitive|null derivedFrom Original definition for the search parameter */
        public ?CanonicalPrimitive $derivedFrom = null,
        /** @var PublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?PublicationStatusType $status = null,
        /** @var bool|null experimental For testing purposes, not real usage */
        public ?bool $experimental = null,
        /** @var DateTimePrimitive|null date Date last changed */
        public ?DateTimePrimitive $date = null,
        /** @var StringPrimitive|string|null publisher Name of the publisher (organization or individual) */
        public StringPrimitive|string|null $publisher = null,
        /** @var array<ContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var MarkdownPrimitive|null description Natural language description of the search parameter */
        #[NotBlank]
        public ?MarkdownPrimitive $description = null,
        /** @var array<UsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<CodeableConcept> jurisdiction Intended jurisdiction for search parameter (if applicable) */
        public array $jurisdiction = [],
        /** @var MarkdownPrimitive|null purpose Why this search parameter is defined */
        public ?MarkdownPrimitive $purpose = null,
        /** @var CodePrimitive|null code Code used in URL */
        #[NotBlank]
        public ?CodePrimitive $code = null,
        /** @var array<ResourceTypeType> base The resource type(s) this search parameter applies to */
        public array $base = [],
        /** @var SearchParamTypeType|null type number | date | string | token | reference | composite | quantity | uri | special */
        #[NotBlank]
        public ?SearchParamTypeType $type = null,
        /** @var StringPrimitive|string|null expression FHIRPath expression that extracts the values */
        public StringPrimitive|string|null $expression = null,
        /** @var StringPrimitive|string|null xpath XPath that extracts the values */
        public StringPrimitive|string|null $xpath = null,
        /** @var XPathUsageTypeType|null xpathUsage normal | phonetic | nearby | distance | other */
        public ?XPathUsageTypeType $xpathUsage = null,
        /** @var array<ResourceTypeType> target Types of resource (if a resource reference) */
        public array $target = [],
        /** @var bool|null multipleOr Allow multiple values per parameter (or) */
        public ?bool $multipleOr = null,
        /** @var bool|null multipleAnd Allow multiple parameters (and) */
        public ?bool $multipleAnd = null,
        /** @var array<SearchComparatorType> comparator eq | ne | gt | lt | ge | le | sa | eb | ap */
        public array $comparator = [],
        /** @var array<SearchModifierCodeType> modifier missing | exact | contains | not | text | in | not-in | below | above | type | identifier | ofType */
        public array $modifier = [],
        /** @var array<StringPrimitive|string> chain Chained names supported */
        public array $chain = [],
        /** @var array<SearchParameterComponent> component For Composite resources to define the parts */
        public array $component = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
