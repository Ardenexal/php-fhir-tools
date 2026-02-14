<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRVersionType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\StructureDefinitionKindType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\TypeDerivationRuleType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureDefinition\StructureDefinitionContext;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureDefinition\StructureDefinitionDifferential;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureDefinition\StructureDefinitionMapping;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureDefinition\StructureDefinitionSnapshot;
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
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/StructureDefinition',
    fhirVersion: 'R4',
)]
class StructureDefinitionResource extends DomainResourceResource
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
        /** @var UriPrimitive|null url Canonical identifier for this structure definition, represented as a URI (globally unique) */
        #[NotBlank]
        public ?UriPrimitive $url = null,
        /** @var array<Identifier> identifier Additional identifier for the structure definition */
        public array $identifier = [],
        /** @var StringPrimitive|string|null version Business version of the structure definition */
        public StringPrimitive|string|null $version = null,
        /** @var StringPrimitive|string|null name Name for this structure definition (computer friendly) */
        #[NotBlank]
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null title Name for this structure definition (human friendly) */
        public StringPrimitive|string|null $title = null,
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
        /** @var MarkdownPrimitive|null description Natural language description of the structure definition */
        public ?MarkdownPrimitive $description = null,
        /** @var array<UsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<CodeableConcept> jurisdiction Intended jurisdiction for structure definition (if applicable) */
        public array $jurisdiction = [],
        /** @var MarkdownPrimitive|null purpose Why this structure definition is defined */
        public ?MarkdownPrimitive $purpose = null,
        /** @var MarkdownPrimitive|null copyright Use and/or publishing restrictions */
        public ?MarkdownPrimitive $copyright = null,
        /** @var array<Coding> keyword Assist with indexing and finding */
        public array $keyword = [],
        /** @var FHIRVersionType|null fhirVersion FHIR Version this StructureDefinition targets */
        public ?FHIRVersionType $fhirVersion = null,
        /** @var array<StructureDefinitionMapping> mapping External specification that the content is mapped to */
        public array $mapping = [],
        /** @var StructureDefinitionKindType|null kind primitive-type | complex-type | resource | logical */
        #[NotBlank]
        public ?StructureDefinitionKindType $kind = null,
        /** @var bool|null abstract Whether the structure is abstract */
        #[NotBlank]
        public ?bool $abstract = null,
        /** @var array<StructureDefinitionContext> context If an extension, where it can be used in instances */
        public array $context = [],
        /** @var array<StringPrimitive|string> contextInvariant FHIRPath invariants - when the extension can be used */
        public array $contextInvariant = [],
        /** @var UriPrimitive|null type Type defined or constrained by this structure */
        #[NotBlank]
        public ?UriPrimitive $type = null,
        /** @var CanonicalPrimitive|null baseDefinition Definition that this type is constrained/specialized from */
        public ?CanonicalPrimitive $baseDefinition = null,
        /** @var TypeDerivationRuleType|null derivation specialization | constraint - How relates to base definition */
        public ?TypeDerivationRuleType $derivation = null,
        /** @var StructureDefinitionSnapshot|null snapshot Snapshot view of the structure */
        public ?StructureDefinitionSnapshot $snapshot = null,
        /** @var StructureDefinitionDifferential|null differential Differential view of the structure */
        public ?StructureDefinitionDifferential $differential = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
