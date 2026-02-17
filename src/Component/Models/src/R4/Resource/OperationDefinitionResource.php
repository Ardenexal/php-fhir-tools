<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\OperationKindType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ResourceTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\OperationDefinition\OperationDefinitionOverload;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\OperationDefinition\OperationDefinitionParameter;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/OperationDefinition
 *
 * @description A formal computable definition of an operation (on the RESTful interface) or a named query (using the search interaction).
 */
#[FhirResource(
    type: 'OperationDefinition',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/OperationDefinition',
    fhirVersion: 'R4',
)]
class OperationDefinitionResource extends DomainResourceResource
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
        /** @var UriPrimitive|null url Canonical identifier for this operation definition, represented as a URI (globally unique) */
        public ?UriPrimitive $url = null,
        /** @var StringPrimitive|string|null version Business version of the operation definition */
        public StringPrimitive|string|null $version = null,
        /** @var StringPrimitive|string|null name Name for this operation definition (computer friendly) */
        #[NotBlank]
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null title Name for this operation definition (human friendly) */
        public StringPrimitive|string|null $title = null,
        /** @var PublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?PublicationStatusType $status = null,
        /** @var OperationKindType|null kind operation | query */
        #[NotBlank]
        public ?OperationKindType $kind = null,
        /** @var bool|null experimental For testing purposes, not real usage */
        public ?bool $experimental = null,
        /** @var DateTimePrimitive|null date Date last changed */
        public ?DateTimePrimitive $date = null,
        /** @var StringPrimitive|string|null publisher Name of the publisher (organization or individual) */
        public StringPrimitive|string|null $publisher = null,
        /** @var array<ContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var MarkdownPrimitive|null description Natural language description of the operation definition */
        public ?MarkdownPrimitive $description = null,
        /** @var array<UsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<CodeableConcept> jurisdiction Intended jurisdiction for operation definition (if applicable) */
        public array $jurisdiction = [],
        /** @var MarkdownPrimitive|null purpose Why this operation definition is defined */
        public ?MarkdownPrimitive $purpose = null,
        /** @var bool|null affectsState Whether content is changed by the operation */
        public ?bool $affectsState = null,
        /** @var CodePrimitive|null code Name used to invoke the operation */
        #[NotBlank]
        public ?CodePrimitive $code = null,
        /** @var MarkdownPrimitive|null comment Additional information about use */
        public ?MarkdownPrimitive $comment = null,
        /** @var CanonicalPrimitive|null base Marks this as a profile of the base */
        public ?CanonicalPrimitive $base = null,
        /** @var array<ResourceTypeType> resource Types this operation applies to */
        public array $resource = [],
        /** @var bool|null system Invoke at the system level? */
        #[NotBlank]
        public ?bool $system = null,
        /** @var bool|null type Invoke at the type level? */
        #[NotBlank]
        public ?bool $type = null,
        /** @var bool|null instance Invoke on an instance? */
        #[NotBlank]
        public ?bool $instance = null,
        /** @var CanonicalPrimitive|null inputProfile Validation information for in parameters */
        public ?CanonicalPrimitive $inputProfile = null,
        /** @var CanonicalPrimitive|null outputProfile Validation information for out parameters */
        public ?CanonicalPrimitive $outputProfile = null,
        /** @var array<OperationDefinitionParameter> parameter Parameters for the operation/query */
        public array $parameter = [],
        /** @var array<OperationDefinitionOverload> overload Define overloaded variants for when  generating code */
        public array $overload = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
