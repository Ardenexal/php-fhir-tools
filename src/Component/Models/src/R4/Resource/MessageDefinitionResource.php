<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\MessageSignificanceCategoryType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\MessageheaderResponseRequestType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MessageDefinition\MessageDefinitionAllowedResponse;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MessageDefinition\MessageDefinitionFocus;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Infrastructure And Messaging)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MessageDefinition
 *
 * @description Defines the characteristics of a message that can be shared between systems, including the type of event that initiates the message, the content to be transmitted and what response(s), if any, are permitted.
 */
#[FhirResource(
    type: 'MessageDefinition',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/MessageDefinition',
    fhirVersion: 'R4',
)]
class MessageDefinitionResource extends DomainResourceResource
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
        /** @var UriPrimitive|null url Business Identifier for a given MessageDefinition */
        public ?UriPrimitive $url = null,
        /** @var array<Identifier> identifier Primary key for the message definition on a given server */
        public array $identifier = [],
        /** @var StringPrimitive|string|null version Business version of the message definition */
        public StringPrimitive|string|null $version = null,
        /** @var StringPrimitive|string|null name Name for this message definition (computer friendly) */
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null title Name for this message definition (human friendly) */
        public StringPrimitive|string|null $title = null,
        /** @var array<CanonicalPrimitive> replaces Takes the place of */
        public array $replaces = [],
        /** @var PublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?PublicationStatusType $status = null,
        /** @var bool|null experimental For testing purposes, not real usage */
        public ?bool $experimental = null,
        /** @var DateTimePrimitive|null date Date last changed */
        #[NotBlank]
        public ?DateTimePrimitive $date = null,
        /** @var StringPrimitive|string|null publisher Name of the publisher (organization or individual) */
        public StringPrimitive|string|null $publisher = null,
        /** @var array<ContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var MarkdownPrimitive|null description Natural language description of the message definition */
        public ?MarkdownPrimitive $description = null,
        /** @var array<UsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<CodeableConcept> jurisdiction Intended jurisdiction for message definition (if applicable) */
        public array $jurisdiction = [],
        /** @var MarkdownPrimitive|null purpose Why this message definition is defined */
        public ?MarkdownPrimitive $purpose = null,
        /** @var MarkdownPrimitive|null copyright Use and/or publishing restrictions */
        public ?MarkdownPrimitive $copyright = null,
        /** @var CanonicalPrimitive|null base Definition this one is based on */
        public ?CanonicalPrimitive $base = null,
        /** @var array<CanonicalPrimitive> parent Protocol/workflow this is part of */
        public array $parent = [],
        /** @var Coding|UriPrimitive|null eventX Event code  or link to the EventDefinition */
        #[NotBlank]
        public Coding|UriPrimitive|null $eventX = null,
        /** @var MessageSignificanceCategoryType|null category consequence | currency | notification */
        public ?MessageSignificanceCategoryType $category = null,
        /** @var array<MessageDefinitionFocus> focus Resource(s) that are the subject of the event */
        public array $focus = [],
        /** @var MessageheaderResponseRequestType|null responseRequired always | on-error | never | on-success */
        public ?MessageheaderResponseRequestType $responseRequired = null,
        /** @var array<MessageDefinitionAllowedResponse> allowedResponse Responses to this message */
        public array $allowedResponse = [],
        /** @var array<CanonicalPrimitive> graph Canonical reference to a GraphDefinition */
        public array $graph = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
