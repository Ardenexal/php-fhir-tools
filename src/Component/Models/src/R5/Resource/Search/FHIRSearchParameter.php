<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/SearchParameter
 *
 * @description A search parameter that defines a named search item that can be used to search/filter on a resource.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'SearchParameter',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/SearchParameter',
    fhirVersion: 'R5',
)]
class FHIRSearchParameter extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?\FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRUri|null url Canonical identifier for this search parameter, represented as a URI (globally unique) */
        #[NotBlank]
        public ?\FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Additional identifier for the search parameter (business identifier) */
        public array $identifier = [],
        /** @var FHIRString|string|null version Business version of the search parameter */
        public \FHIRString|string|null $version = null,
        /** @var FHIRString|string|FHIRCoding|null versionAlgorithmX How to compare versions */
        public \FHIRString|string|\FHIRCoding|null $versionAlgorithmX = null,
        /** @var FHIRString|string|null name Name for this search parameter (computer friendly) */
        #[NotBlank]
        public \FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this search parameter (human friendly) */
        public \FHIRString|string|null $title = null,
        /** @var FHIRCanonical|null derivedFrom Original definition for the search parameter */
        public ?\FHIRCanonical $derivedFrom = null,
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?\FHIRPublicationStatusType $status = null,
        /** @var FHIRBoolean|null experimental For testing purposes, not real usage */
        public ?\FHIRBoolean $experimental = null,
        /** @var FHIRDateTime|null date Date last changed */
        public ?\FHIRDateTime $date = null,
        /** @var FHIRString|string|null publisher Name of the publisher/steward (organization or individual) */
        public \FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var FHIRMarkdown|null description Natural language description of the search parameter */
        #[NotBlank]
        public ?\FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for search parameter (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null purpose Why this search parameter is defined */
        public ?\FHIRMarkdown $purpose = null,
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions */
        public ?\FHIRMarkdown $copyright = null,
        /** @var FHIRString|string|null copyrightLabel Copyright holder and year(s) */
        public \FHIRString|string|null $copyrightLabel = null,
        /** @var FHIRCode|null code Recommended name for parameter in search url */
        #[NotBlank]
        public ?\FHIRCode $code = null,
        /** @var array<FHIRVersionIndependentResourceTypesAllType> base The resource type(s) this search parameter applies to */
        public array $base = [],
        /** @var FHIRSearchParamTypeType|null type number | date | string | token | reference | composite | quantity | uri | special */
        #[NotBlank]
        public ?\FHIRSearchParamTypeType $type = null,
        /** @var FHIRString|string|null expression FHIRPath expression that extracts the values */
        public \FHIRString|string|null $expression = null,
        /** @var FHIRSearchProcessingModeTypeType|null processingMode normal | phonetic | other */
        public ?\FHIRSearchProcessingModeTypeType $processingMode = null,
        /** @var FHIRString|string|null constraint FHIRPath expression that constraints the usage of this SearchParamete */
        public \FHIRString|string|null $constraint = null,
        /** @var array<FHIRVersionIndependentResourceTypesAllType> target Types of resource (if a resource reference) */
        public array $target = [],
        /** @var FHIRBoolean|null multipleOr Allow multiple values per parameter (or) */
        public ?\FHIRBoolean $multipleOr = null,
        /** @var FHIRBoolean|null multipleAnd Allow multiple parameters (and) */
        public ?\FHIRBoolean $multipleAnd = null,
        /** @var array<FHIRSearchComparatorType> comparator eq | ne | gt | lt | ge | le | sa | eb | ap */
        public array $comparator = [],
        /** @var array<FHIRSearchModifierCodeType> modifier missing | exact | contains | not | text | in | not-in | below | above | type | identifier | of-type | code-text | text-advanced | iterate */
        public array $modifier = [],
        /** @var array<FHIRString|string> chain Chained names supported */
        public array $chain = [],
        /** @var array<FHIRSearchParameterComponent> component For Composite resources to define the parts */
        public array $component = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
