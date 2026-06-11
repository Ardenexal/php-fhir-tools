<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRVersionType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\StructureDefinitionKindType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\TypeDerivationRuleType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\StructureDefinition\StructureDefinitionContext;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\StructureDefinition\StructureDefinitionDifferential;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\StructureDefinition\StructureDefinitionMapping;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\StructureDefinition\StructureDefinitionSnapshot;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/StructureDefinition
 *
 * @description A definition of a FHIR structure. This resource is used to describe the underlying resources, data types defined in FHIR, and also for describing extensions and constraints on resources and data types.
 */
#[FhirResource(
    type: 'StructureDefinition',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/StructureDefinition',
    fhirVersion: 'R5',
)]
#[FHIRPathInvariant(
    key: 'cnl-0',
    severity: 'warning',
    expression: 'name.exists() implies name.matches(\'^[A-Z]([A-Za-z0-9_]){1,254}$\')',
    human: 'Name should be usable as an identifier for the module by machine processing applications such as code generation',
)]
#[FHIRPathInvariant(
    key: 'sdf-1',
    severity: 'error',
    expression: 'derivation = \'constraint\' or snapshot.element.select(path).isDistinct()',
    human: 'Element paths must be unique unless the structure is a constraint',
)]
#[FHIRPathInvariant(
    key: 'sdf-4',
    severity: 'error',
    expression: 'abstract = true or baseDefinition.exists()',
    human: 'If the structure is not abstract, then there SHALL be a baseDefinition',
)]
#[FHIRPathInvariant(
    key: 'sdf-5',
    severity: 'error',
    expression: 'type != \'Extension\' or derivation = \'specialization\' or (context.exists())',
    human: 'If the structure defines an extension then the structure must have context information',
)]
#[FHIRPathInvariant(
    key: 'sdf-6',
    severity: 'error',
    expression: 'snapshot.exists() or differential.exists()',
    human: 'A structure must have either a differential, or a snapshot (or both)',
)]
#[FHIRPathInvariant(
    key: 'sdf-11',
    severity: 'error',
    expression: 'kind != \'logical\' implies snapshot.empty() or snapshot.element.first().path = type',
    human: 'If there\'s a type, its content must match the path name in the first element of a snapshot',
)]
#[FHIRPathInvariant(
    key: 'sdf-14',
    severity: 'error',
    expression: 'snapshot.element.all(id.exists()) and differential.element.all(id.exists())',
    human: 'All element definitions must have an id',
)]
#[FHIRPathInvariant(
    key: 'sdf-15',
    severity: 'error',
    expression: 'kind!=\'logical\'  implies snapshot.element.first().type.empty()',
    human: 'The first element in a snapshot has no type unless model is a logical model.',
)]
#[FHIRPathInvariant(
    key: 'sdf-15a',
    severity: 'error',
    expression: '(kind!=\'logical\'  and differential.element.first().path.contains(\'.\').not()) implies differential.element.first().type.empty()',
    human: 'If the first element in a differential has no "." in the path and it\'s not a logical model, it has no type',
)]
#[FHIRPathInvariant(
    key: 'sdf-9',
    severity: 'error',
    expression: 'children().element.where(path.contains(\'.\').not()).label.empty() and children().element.where(path.contains(\'.\').not()).code.empty() and children().element.where(path.contains(\'.\').not()).requirements.empty()',
    human: 'In any snapshot or differential, no label, code or requirements on an element without a "." in the path (e.g. the first element)',
)]
#[FHIRPathInvariant(
    key: 'sdf-16',
    severity: 'error',
    expression: 'snapshot.element.all(id.exists()) and snapshot.element.id.trace(\'ids\').isDistinct()',
    human: 'All element definitions must have unique ids (snapshot)',
)]
#[FHIRPathInvariant(
    key: 'sdf-17',
    severity: 'error',
    expression: 'differential.element.all(id.exists()) and differential.element.id.trace(\'ids\').isDistinct()',
    human: 'All element definitions must have unique ids (diff)',
)]
#[FHIRPathInvariant(
    key: 'sdf-18',
    severity: 'error',
    expression: 'contextInvariant.exists() implies type = \'Extension\'',
    human: 'Context Invariants can only be used for extensions',
)]
#[FHIRPathInvariant(
    key: 'sdf-19',
    severity: 'error',
    expression: 'url.startsWith(\'http://hl7.org/fhir/StructureDefinition\') implies (differential | snapshot).element.type.code.all(matches(\'^[a-zA-Z0-9]+$\') or matches(\'^http:\\\/\\\/hl7\\\.org\\\/fhirpath\\\/System\\\.[A-Z][A-Za-z]+$\'))',
    human: 'FHIR Specification models only use FHIR defined types',
)]
#[FHIRPathInvariant(
    key: 'sdf-21',
    severity: 'error',
    expression: 'differential.element.defaultValue.exists() implies (derivation = \'specialization\')',
    human: 'Default values can only be specified on specializations',
)]
#[FHIRPathInvariant(
    key: 'sdf-22',
    severity: 'error',
    expression: 'url.startsWith(\'http://hl7.org/fhir/StructureDefinition\') implies (snapshot.element.defaultValue.empty() and differential.element.defaultValue.empty())',
    human: 'FHIR Specification models never have default values',
)]
#[FHIRPathInvariant(
    key: 'sdf-23',
    severity: 'error',
    expression: '(snapshot | differential).element.all(path.contains(\'.\').not() implies sliceName.empty())',
    human: 'No slice name on root',
)]
#[FHIRPathInvariant(
    key: 'sdf-27',
    severity: 'error',
    expression: 'baseDefinition.exists() implies derivation.exists()',
    human: 'If there\'s a base definition, there must be a derivation ',
)]
#[FHIRPathInvariant(
    key: 'sdf-29',
    severity: 'warning',
    expression: '((kind in \'resource\' | \'complex-type\') and (derivation = \'specialization\')) implies differential.element.where((min != 0 and min != 1) or (max != \'1\' and max != \'*\')).empty()',
    human: 'Elements in Resources must have a min cardinality or 0 or 1 and a max cardinality of 1 or *',
)]
class StructureDefinitionResource extends DomainResourceResource
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
        /** @var array<ResourceResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the resource that contains them')]
        public array $modifierExtension = [],
        /** @var UriPrimitive|null url Canonical identifier for this structure definition, represented as a URI (globally unique) */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?UriPrimitive $url = null,
        /** @var array<Identifier> identifier Additional identifier for the structure definition */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var StringPrimitive|string|null version Business version of the structure definition */
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
        /** @var StringPrimitive|string|null name Name for this structure definition (computer friendly) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isRequired: true), NotBlank]
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null title Name for this structure definition (human friendly) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $title = null,
        /** @var PublicationStatusType|null status draft | active | retired | unknown */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/publication-status|5.0.0', strength: 'required'), FHIRIsModifier(reason: 'This is labeled as "Is Modifier" because applications should not use a retired {{title}} without due consideration')]
        public ?PublicationStatusType $status = null,
        /** @var bool|null experimental For testing purposes, not real usage */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $experimental = null,
        /** @var DateTimePrimitive|null date Date last changed */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
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
        /** @var MarkdownPrimitive|null description Natural language description of the structure definition */
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
        /** @var array<CodeableConcept> jurisdiction Intended jurisdiction for structure definition (if applicable) */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        #[FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/jurisdiction', strength: 'extensible')]
        public array $jurisdiction = [],
        /** @var MarkdownPrimitive|null purpose Why this structure definition is defined */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $purpose = null,
        /** @var MarkdownPrimitive|null copyright Use and/or publishing restrictions */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $copyright = null,
        /** @var StringPrimitive|string|null copyrightLabel Copyright holder and year(s) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $copyrightLabel = null,
        /** @var array<Coding> keyword Assist with indexing and finding */
        #[FhirProperty(
            fhirType: 'Coding',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding',
        )]
        #[FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/definition-use', strength: 'extensible')]
        public array $keyword = [],
        /** @var FHIRVersionType|null fhirVersion FHIR Version this StructureDefinition targets */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/FHIR-version|5.0.0', strength: 'required')]
        public ?FHIRVersionType $fhirVersion = null,
        /** @var array<StructureDefinitionMapping> mapping External specification that the content is mapped to */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\StructureDefinition\StructureDefinitionMapping',
        )]
        public array $mapping = [],
        /** @var StructureDefinitionKindType|null kind primitive-type | complex-type | resource | logical */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/structure-definition-kind|5.0.0', strength: 'required')]
        public ?StructureDefinitionKindType $kind = null,
        /** @var bool|null abstract Whether the structure is abstract */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar', isRequired: true), NotBlank]
        public ?bool $abstract = null,
        /** @var array<StructureDefinitionContext> context If an extension, where it can be used in instances */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\StructureDefinition\StructureDefinitionContext',
        )]
        public array $context = [],
        /** @var array<StringPrimitive|string> contextInvariant FHIRPath invariants - when the extension can be used */
        #[FhirProperty(
            fhirType: 'string',
            propertyKind: 'primitive',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive',
        )]
        public array $contextInvariant = [],
        /** @var UriPrimitive|null type Type defined or constrained by this structure */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/fhir-types', strength: 'extensible')]
        public ?UriPrimitive $type = null,
        /** @var CanonicalPrimitive|null baseDefinition Definition that this type is constrained/specialized from */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/StructureDefinition'])]
        public ?CanonicalPrimitive $baseDefinition = null,
        /** @var TypeDerivationRuleType|null derivation specialization | constraint - How relates to base definition */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/type-derivation-rule|5.0.0', strength: 'required')]
        public ?TypeDerivationRuleType $derivation = null,
        /** @var StructureDefinitionSnapshot|null snapshot Snapshot view of the structure */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?StructureDefinitionSnapshot $snapshot = null,
        /** @var StructureDefinitionDifferential|null differential Differential view of the structure */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?StructureDefinitionDifferential $differential = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
