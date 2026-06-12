<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CapabilityStatementKindType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeSearchSupportType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\TerminologyCapabilities\TerminologyCapabilitiesClosure;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\TerminologyCapabilities\TerminologyCapabilitiesCodeSystem;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\TerminologyCapabilities\TerminologyCapabilitiesExpansion;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\TerminologyCapabilities\TerminologyCapabilitiesImplementation;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\TerminologyCapabilities\TerminologyCapabilitiesSoftware;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\TerminologyCapabilities\TerminologyCapabilitiesTranslation;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\TerminologyCapabilities\TerminologyCapabilitiesValidateCode;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Terminology Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/TerminologyCapabilities
 *
 * @description A TerminologyCapabilities resource documents a set of capabilities (behaviors) of a FHIR Terminology Server that may be used as a statement of actual server functionality or a statement of required or desired server implementation.
 */
#[FhirResource(
    type: 'TerminologyCapabilities',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/TerminologyCapabilities',
    fhirVersion: 'R5',
)]
#[FHIRPathInvariant(
    key: 'cnl-0',
    severity: 'warning',
    expression: 'name.exists() implies name.matches(\'^[A-Z]([A-Za-z0-9_]){1,254}$\')',
    human: 'Name should be usable as an identifier for the module by machine processing applications such as code generation',
)]
#[FHIRPathInvariant(
    key: 'tcp-2',
    severity: 'error',
    expression: '(description.count() + software.count() + implementation.count()) > 0',
    human: 'A Terminology Capability statement SHALL have at least one of description, software, or implementation element',
)]
#[FHIRPathInvariant(
    key: 'tcp-3',
    severity: 'error',
    expression: '(kind != \'instance\') or implementation.exists()',
    human: 'If kind = instance, implementation must be present and software may be present',
)]
#[FHIRPathInvariant(
    key: 'tcp-4',
    severity: 'error',
    expression: '(kind != \'capability\') or (implementation.exists().not() and software.exists())',
    human: 'If kind = capability, implementation must be absent, software must be present',
)]
#[FHIRPathInvariant(
    key: 'tcp-5',
    severity: 'error',
    expression: '(kind!=\'requirements\') or (implementation.exists().not() and software.exists().not())',
    human: 'If kind = requirements, implementation and software must be absent',
)]
#[FHIRPathInvariant(
    key: 'tcp-6',
    severity: 'error',
    expression: 'codeSystem.uri.isDistinct()',
    human: 'Each instance of the codeSystem element must represent a distinct code system.',
)]
class TerminologyCapabilitiesResource extends AbstractDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive'), FHIRIsModifier(reason: 'This element is labeled as a modifier because the implicit rules may provide additional knowledge about the resource that modifies its meaning or interpretation')]
        public ?UriPrimitive $implicitRules = null,
        /** @var AllLanguagesType|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/all-languages|5.0.0', strength: 'required')]
        public ?AllLanguagesType $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<AbstractResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the resource that contains them')]
        public array $modifierExtension = [],
        /** @var UriPrimitive|null url Canonical identifier for this terminology capabilities, represented as a URI (globally unique) */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $url = null,
        /** @var array<Identifier> identifier Additional identifier for the terminology capabilities */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var StringPrimitive|string|null version Business version of the terminology capabilities */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $version = null,
        /** @var StringPrimitive|string|Coding|null versionAlgorithm How to compare versions */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive',
                    'jsonKey'      => 'versionAlgorithmString',
                ],
                [
                    'fhirType'     => 'Coding',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding',
                    'jsonKey'      => 'versionAlgorithmCoding',
                ],
            ],
        )]
        #[FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/version-algorithm', strength: 'extensible')]
        public StringPrimitive|string|Coding|null $versionAlgorithm = null,
        /** @var StringPrimitive|string|null name Name for this terminology capabilities (computer friendly) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null title Name for this terminology capabilities (human friendly) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $title = null,
        /** @var PublicationStatusType|null status draft | active | retired | unknown */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/publication-status|5.0.0', strength: 'required'), FHIRIsModifier(reason: 'This is labeled as "Is Modifier" because applications should not use a retired {{title}} without due consideration')]
        public ?PublicationStatusType $status = null,
        /** @var bool|null experimental For testing purposes, not real usage */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $experimental = null,
        /** @var DateTimePrimitive|null date Date last changed */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?DateTimePrimitive $date = null,
        /** @var StringPrimitive|string|null publisher Name of the publisher/steward (organization or individual) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $publisher = null,
        /** @var array<ContactDetail> contact Contact details for the publisher */
        #[FhirProperty(
            fhirType: 'ContactDetail',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ContactDetail',
        )]
        public array $contact = [],
        /** @var MarkdownPrimitive|null description Natural language description of the terminology capabilities */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $description = null,
        /** @var array<UsageContext> useContext The context that the content is intended to support */
        #[FhirProperty(
            fhirType: 'UsageContext',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\UsageContext',
        )]
        public array $useContext = [],
        /** @var array<CodeableConcept> jurisdiction Intended jurisdiction for terminology capabilities (if applicable) */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        #[FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/jurisdiction', strength: 'extensible')]
        public array $jurisdiction = [],
        /** @var MarkdownPrimitive|null purpose Why this terminology capabilities is defined */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $purpose = null,
        /** @var MarkdownPrimitive|null copyright Use and/or publishing restrictions */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $copyright = null,
        /** @var StringPrimitive|string|null copyrightLabel Copyright holder and year(s) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $copyrightLabel = null,
        /** @var CapabilityStatementKindType|null kind instance | capability | requirements */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/capability-statement-kind|5.0.0', strength: 'required')]
        public ?CapabilityStatementKindType $kind = null,
        /** @var TerminologyCapabilitiesSoftware|null software Software that is covered by this terminology capability statement */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?TerminologyCapabilitiesSoftware $software = null,
        /** @var TerminologyCapabilitiesImplementation|null implementation If this describes a specific instance */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?TerminologyCapabilitiesImplementation $implementation = null,
        /** @var bool|null lockedDate Whether lockedDate is supported */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $lockedDate = null,
        /** @var array<TerminologyCapabilitiesCodeSystem> codeSystem A code system supported by the server */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\TerminologyCapabilities\TerminologyCapabilitiesCodeSystem',
        )]
        public array $codeSystem = [],
        /** @var TerminologyCapabilitiesExpansion|null expansion Information about the [ValueSet/$expand](valueset-operation-expand.html) operation */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?TerminologyCapabilitiesExpansion $expansion = null,
        /** @var CodeSearchSupportType|null codeSearch in-compose | in-expansion | in-compose-or-expansion */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/code-search-support|5.0.0', strength: 'required')]
        public ?CodeSearchSupportType $codeSearch = null,
        /** @var TerminologyCapabilitiesValidateCode|null validateCode Information about the [ValueSet/$validate-code](valueset-operation-validate-code.html) operation */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?TerminologyCapabilitiesValidateCode $validateCode = null,
        /** @var TerminologyCapabilitiesTranslation|null translation Information about the [ConceptMap/$translate](conceptmap-operation-translate.html) operation */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?TerminologyCapabilitiesTranslation $translation = null,
        /** @var TerminologyCapabilitiesClosure|null closure Information about the [ConceptMap/$closure](conceptmap-operation-closure.html) operation */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?TerminologyCapabilitiesClosure $closure = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
