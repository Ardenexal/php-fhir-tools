<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRResourceTypeType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRSearchComparatorType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRSearchModifierCodeType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRSearchParamTypeType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRUsageContext;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRXPathUsageTypeType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
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
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/SearchParameter',
    fhirVersion: 'R4B',
)]
class FHIRSearchParameter extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRUri|null url Canonical identifier for this search parameter, represented as a URI (globally unique) */
        #[NotBlank]
        public ?FHIRUri $url = null,
        /** @var FHIRString|string|null version Business version of the search parameter */
        public FHIRString|string|null $version = null,
        /** @var FHIRString|string|null name Name for this search parameter (computer friendly) */
        #[NotBlank]
        public FHIRString|string|null $name = null,
        /** @var FHIRCanonical|null derivedFrom Original definition for the search parameter */
        public ?FHIRCanonical $derivedFrom = null,
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?FHIRPublicationStatusType $status = null,
        /** @var FHIRBoolean|null experimental For testing purposes, not real usage */
        public ?FHIRBoolean $experimental = null,
        /** @var FHIRDateTime|null date Date last changed */
        public ?FHIRDateTime $date = null,
        /** @var FHIRString|string|null publisher Name of the publisher (organization or individual) */
        public FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var FHIRMarkdown|null description Natural language description of the search parameter */
        #[NotBlank]
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for search parameter (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null purpose Why this search parameter is defined */
        public ?FHIRMarkdown $purpose = null,
        /** @var FHIRCode|null code Code used in URL */
        #[NotBlank]
        public ?FHIRCode $code = null,
        /** @var array<FHIRResourceTypeType> base The resource type(s) this search parameter applies to */
        public array $base = [],
        /** @var FHIRSearchParamTypeType|null type number | date | string | token | reference | composite | quantity | uri | special */
        #[NotBlank]
        public ?FHIRSearchParamTypeType $type = null,
        /** @var FHIRString|string|null expression FHIRPath expression that extracts the values */
        public FHIRString|string|null $expression = null,
        /** @var FHIRString|string|null xpath XPath that extracts the values */
        public FHIRString|string|null $xpath = null,
        /** @var FHIRXPathUsageTypeType|null xpathUsage normal | phonetic | nearby | distance | other */
        public ?FHIRXPathUsageTypeType $xpathUsage = null,
        /** @var array<FHIRResourceTypeType> target Types of resource (if a resource reference) */
        public array $target = [],
        /** @var FHIRBoolean|null multipleOr Allow multiple values per parameter (or) */
        public ?FHIRBoolean $multipleOr = null,
        /** @var FHIRBoolean|null multipleAnd Allow multiple parameters (and) */
        public ?FHIRBoolean $multipleAnd = null,
        /** @var array<FHIRSearchComparatorType> comparator eq | ne | gt | lt | ge | le | sa | eb | ap */
        public array $comparator = [],
        /** @var array<FHIRSearchModifierCodeType> modifier missing | exact | contains | not | text | in | not-in | below | above | type | identifier | ofType */
        public array $modifier = [],
        /** @var array<FHIRString|string> chain Chained names supported */
        public array $chain = [],
        /** @var array<FHIRSearchParameterComponent> component For Composite resources to define the parts */
        public array $component = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
