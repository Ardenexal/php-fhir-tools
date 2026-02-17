<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CapabilityStatementKindType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRVersionType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\MimeTypesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement\CapabilityStatementDocument;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement\CapabilityStatementImplementation;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement\CapabilityStatementMessaging;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement\CapabilityStatementRest;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement\CapabilityStatementSoftware;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/CapabilityStatement
 *
 * @description A Capability Statement documents a set of capabilities (behaviors) of a FHIR Server for a particular version of FHIR that may be used as a statement of actual server functionality or a statement of required or desired server implementation.
 */
#[FhirResource(
    type: 'CapabilityStatement',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/CapabilityStatement',
    fhirVersion: 'R4',
)]
class CapabilityStatementResource extends DomainResourceResource
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
        /** @var UriPrimitive|null url Canonical identifier for this capability statement, represented as a URI (globally unique) */
        public ?UriPrimitive $url = null,
        /** @var StringPrimitive|string|null version Business version of the capability statement */
        public StringPrimitive|string|null $version = null,
        /** @var StringPrimitive|string|null name Name for this capability statement (computer friendly) */
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null title Name for this capability statement (human friendly) */
        public StringPrimitive|string|null $title = null,
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
        /** @var MarkdownPrimitive|null description Natural language description of the capability statement */
        public ?MarkdownPrimitive $description = null,
        /** @var array<UsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<CodeableConcept> jurisdiction Intended jurisdiction for capability statement (if applicable) */
        public array $jurisdiction = [],
        /** @var MarkdownPrimitive|null purpose Why this capability statement is defined */
        public ?MarkdownPrimitive $purpose = null,
        /** @var MarkdownPrimitive|null copyright Use and/or publishing restrictions */
        public ?MarkdownPrimitive $copyright = null,
        /** @var CapabilityStatementKindType|null kind instance | capability | requirements */
        #[NotBlank]
        public ?CapabilityStatementKindType $kind = null,
        /** @var array<CanonicalPrimitive> instantiates Canonical URL of another capability statement this implements */
        public array $instantiates = [],
        /** @var array<CanonicalPrimitive> imports Canonical URL of another capability statement this adds to */
        public array $imports = [],
        /** @var CapabilityStatementSoftware|null software Software that is covered by this capability statement */
        public ?CapabilityStatementSoftware $software = null,
        /** @var CapabilityStatementImplementation|null implementation If this describes a specific instance */
        public ?CapabilityStatementImplementation $implementation = null,
        /** @var FHIRVersionType|null fhirVersion FHIR Version the system supports */
        #[NotBlank]
        public ?FHIRVersionType $fhirVersion = null,
        /** @var array<MimeTypesType> format formats supported (xml | json | ttl | mime type) */
        public array $format = [],
        /** @var array<MimeTypesType> patchFormat Patch formats supported */
        public array $patchFormat = [],
        /** @var array<CanonicalPrimitive> implementationGuide Implementation guides supported */
        public array $implementationGuide = [],
        /** @var array<CapabilityStatementRest> rest If the endpoint is a RESTful one */
        public array $rest = [],
        /** @var array<CapabilityStatementMessaging> messaging If messaging is supported */
        public array $messaging = [],
        /** @var array<CapabilityStatementDocument> document Document definition */
        public array $document = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
