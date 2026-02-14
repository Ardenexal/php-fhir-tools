<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeSystemContentModeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeSystemHierarchyMeaningType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CodeSystem\CodeSystemConcept;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CodeSystem\CodeSystemFilter;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CodeSystem\CodeSystemProperty;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Vocabulary)
 *
 * @see http://hl7.org/fhir/StructureDefinition/CodeSystem
 *
 * @description The CodeSystem resource is used to declare the existence of and describe a code system or code system supplement and its key properties, and optionally define a part or all of its content.
 */
#[FhirResource(type: 'CodeSystem', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/CodeSystem', fhirVersion: 'R4')]
class CodeSystemResource extends DomainResourceResource
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
        /** @var UriPrimitive|null url Canonical identifier for this code system, represented as a URI (globally unique) (Coding.system) */
        public ?UriPrimitive $url = null,
        /** @var array<Identifier> identifier Additional identifier for the code system (business identifier) */
        public array $identifier = [],
        /** @var StringPrimitive|string|null version Business version of the code system (Coding.version) */
        public StringPrimitive|string|null $version = null,
        /** @var StringPrimitive|string|null name Name for this code system (computer friendly) */
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null title Name for this code system (human friendly) */
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
        /** @var MarkdownPrimitive|null description Natural language description of the code system */
        public ?MarkdownPrimitive $description = null,
        /** @var array<UsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<CodeableConcept> jurisdiction Intended jurisdiction for code system (if applicable) */
        public array $jurisdiction = [],
        /** @var MarkdownPrimitive|null purpose Why this code system is defined */
        public ?MarkdownPrimitive $purpose = null,
        /** @var MarkdownPrimitive|null copyright Use and/or publishing restrictions */
        public ?MarkdownPrimitive $copyright = null,
        /** @var bool|null caseSensitive If code comparison is case sensitive */
        public ?bool $caseSensitive = null,
        /** @var CanonicalPrimitive|null valueSet Canonical reference to the value set with entire code system */
        public ?CanonicalPrimitive $valueSet = null,
        /** @var CodeSystemHierarchyMeaningType|null hierarchyMeaning grouped-by | is-a | part-of | classified-with */
        public ?CodeSystemHierarchyMeaningType $hierarchyMeaning = null,
        /** @var bool|null compositional If code system defines a compositional grammar */
        public ?bool $compositional = null,
        /** @var bool|null versionNeeded If definitions are not stable */
        public ?bool $versionNeeded = null,
        /** @var CodeSystemContentModeType|null content not-present | example | fragment | complete | supplement */
        #[NotBlank]
        public ?CodeSystemContentModeType $content = null,
        /** @var CanonicalPrimitive|null supplements Canonical URL of Code System this adds designations and properties to */
        public ?CanonicalPrimitive $supplements = null,
        /** @var UnsignedIntPrimitive|null count Total concepts in the code system */
        public ?UnsignedIntPrimitive $count = null,
        /** @var array<CodeSystemFilter> filter Filter that can be used in a value set */
        public array $filter = [],
        /** @var array<CodeSystemProperty> property Additional information supplied about each concept */
        public array $property = [],
        /** @var array<CodeSystemConcept> concept Concepts in the code system */
        public array $concept = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
