<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\ResourceTypeType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\SearchComparatorType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\SearchModifierCodeType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\SearchParamTypeType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\XPathUsageTypeType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\SearchParameter\SearchParameterComponent;
use Symfony\Component\Validator\Constraints\Count;
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
#[FHIRPathInvariant(
    key: 'spd-0',
    severity: 'warning',
    expression: 'name.exists() implies name.matches(\'[A-Z]([A-Za-z0-9_]){0,254}\')',
    human: 'Name should be usable as an identifier for the module by machine processing applications such as code generation',
)]
#[FHIRPathInvariant(
    key: 'spd-1',
    severity: 'error',
    expression: 'xpath.empty() or xpathUsage.exists()',
    human: 'If an xpath is present, there SHALL be an xpathUsage',
)]
#[FHIRPathInvariant(
    key: 'spd-2',
    severity: 'error',
    expression: 'chain.empty() or type = \'reference\'',
    human: 'Search parameters can only have chain names when the search parameter type is \'reference\'',
)]
class SearchParameterResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive'), FHIRIsModifier(reason: 'This element is labeled as a modifier because the implicit rules may provide additional knowledge about the resource that modifies it\'s meaning or interpretation')]
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        #[FHIRValueSetBinding(
            valueSetUrl: 'http://hl7.org/fhir/ValueSet/languages',
            strength: 'preferred',
            maxValueSetUrl: 'http://hl7.org/fhir/ValueSet/all-languages',
        )]
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the resource that contains them')]
        public array $modifierExtension = [],
        /** @var UriPrimitive|null url Canonical identifier for this search parameter, represented as a URI (globally unique) */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?UriPrimitive $url = null,
        /** @var StringPrimitive|string|null version Business version of the search parameter */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $version = null,
        /** @var StringPrimitive|string|null name Name for this search parameter (computer friendly) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isRequired: true), NotBlank]
        public StringPrimitive|string|null $name = null,
        /** @var CanonicalPrimitive|null derivedFrom Original definition for the search parameter */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/SearchParameter'])]
        public ?CanonicalPrimitive $derivedFrom = null,
        /** @var PublicationStatusType|null status draft | active | retired | unknown */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/publication-status|4.3.0', strength: 'required'), FHIRIsModifier(reason: 'This is labeled as "Is Modifier" because applications should not use a retired {{title}} without due consideration')]
        public ?PublicationStatusType $status = null,
        /** @var bool|null experimental For testing purposes, not real usage */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $experimental = null,
        /** @var DateTimePrimitive|null date Date last changed */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $date = null,
        /** @var StringPrimitive|string|null publisher Name of the publisher (organization or individual) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $publisher = null,
        /** @var array<ContactDetail> contact Contact details for the publisher */
        #[FhirProperty(
            fhirType: 'ContactDetail',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\ContactDetail',
        )]
        public array $contact = [],
        /** @var MarkdownPrimitive|null description Natural language description of the search parameter */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?MarkdownPrimitive $description = null,
        /** @var array<UsageContext> useContext The context that the content is intended to support */
        #[FhirProperty(
            fhirType: 'UsageContext',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\UsageContext',
        )]
        public array $useContext = [],
        /** @var array<CodeableConcept> jurisdiction Intended jurisdiction for search parameter (if applicable) */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
        )]
        #[FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/jurisdiction', strength: 'extensible')]
        public array $jurisdiction = [],
        /** @var MarkdownPrimitive|null purpose Why this search parameter is defined */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $purpose = null,
        /** @var CodePrimitive|null code Code used in URL */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?CodePrimitive $code = null,
        /** @var array<ResourceTypeType> base The resource type(s) this search parameter applies to */
        #[FhirProperty(
            fhirType: 'code',
            propertyKind: 'primitive',
            isArray: true,
            isRequired: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\ResourceTypeType',
        )]
        #[Count(min: 1)]
        #[FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/resource-types|4.3.0', strength: 'required')]
        public array $base = [],
        /** @var SearchParamTypeType|null type number | date | string | token | reference | composite | quantity | uri | special */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/search-param-type|4.3.0', strength: 'required')]
        public ?SearchParamTypeType $type = null,
        /** @var StringPrimitive|string|null expression FHIRPath expression that extracts the values */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $expression = null,
        /** @var StringPrimitive|string|null xpath XPath that extracts the values */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $xpath = null,
        /** @var XPathUsageTypeType|null xpathUsage normal | phonetic | nearby | distance | other */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/search-xpath-usage|4.3.0', strength: 'required')]
        public ?XPathUsageTypeType $xpathUsage = null,
        /** @var array<ResourceTypeType> target Types of resource (if a resource reference) */
        #[FhirProperty(
            fhirType: 'code',
            propertyKind: 'primitive',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\ResourceTypeType',
        )]
        #[FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/resource-types|4.3.0', strength: 'required')]
        public array $target = [],
        /** @var bool|null multipleOr Allow multiple values per parameter (or) */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $multipleOr = null,
        /** @var bool|null multipleAnd Allow multiple parameters (and) */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $multipleAnd = null,
        /** @var array<SearchComparatorType> comparator eq | ne | gt | lt | ge | le | sa | eb | ap */
        #[FhirProperty(
            fhirType: 'code',
            propertyKind: 'primitive',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\SearchComparatorType',
        )]
        #[FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/search-comparator|4.3.0', strength: 'required')]
        public array $comparator = [],
        /** @var array<SearchModifierCodeType> modifier missing | exact | contains | not | text | in | not-in | below | above | type | identifier | ofType */
        #[FhirProperty(
            fhirType: 'code',
            propertyKind: 'primitive',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\SearchModifierCodeType',
        )]
        #[FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/search-modifier-code|4.3.0', strength: 'required')]
        public array $modifier = [],
        /** @var array<StringPrimitive|string> chain Chained names supported */
        #[FhirProperty(
            fhirType: 'string',
            propertyKind: 'primitive',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive',
        )]
        public array $chain = [],
        /** @var array<SearchParameterComponent> component For Composite resources to define the parts */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\SearchParameter\SearchParameterComponent',
        )]
        public array $component = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
