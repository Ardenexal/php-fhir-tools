<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CapabilityStatementKindType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeSearchSupportType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities\TerminologyCapabilitiesClosure;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities\TerminologyCapabilitiesCodeSystem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities\TerminologyCapabilitiesExpansion;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities\TerminologyCapabilitiesImplementation;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities\TerminologyCapabilitiesSoftware;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities\TerminologyCapabilitiesTranslation;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities\TerminologyCapabilitiesValidateCode;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Vocabulary)
 *
 * @see http://hl7.org/fhir/StructureDefinition/TerminologyCapabilities
 *
 * @description A TerminologyCapabilities resource documents a set of capabilities (behaviors) of a FHIR Terminology Server that may be used as a statement of actual server functionality or a statement of required or desired server implementation.
 */
#[FhirResource(
    type: 'TerminologyCapabilities',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/TerminologyCapabilities',
    fhirVersion: 'R4',
)]
class TerminologyCapabilitiesResource extends DomainResourceResource
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
        /** @var UriPrimitive|null url Canonical identifier for this terminology capabilities, represented as a URI (globally unique) */
        public ?UriPrimitive $url = null,
        /** @var StringPrimitive|string|null version Business version of the terminology capabilities */
        public StringPrimitive|string|null $version = null,
        /** @var StringPrimitive|string|null name Name for this terminology capabilities (computer friendly) */
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null title Name for this terminology capabilities (human friendly) */
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
        /** @var MarkdownPrimitive|null description Natural language description of the terminology capabilities */
        public ?MarkdownPrimitive $description = null,
        /** @var array<UsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<CodeableConcept> jurisdiction Intended jurisdiction for terminology capabilities (if applicable) */
        public array $jurisdiction = [],
        /** @var MarkdownPrimitive|null purpose Why this terminology capabilities is defined */
        public ?MarkdownPrimitive $purpose = null,
        /** @var MarkdownPrimitive|null copyright Use and/or publishing restrictions */
        public ?MarkdownPrimitive $copyright = null,
        /** @var CapabilityStatementKindType|null kind instance | capability | requirements */
        #[NotBlank]
        public ?CapabilityStatementKindType $kind = null,
        /** @var TerminologyCapabilitiesSoftware|null software Software that is covered by this terminology capability statement */
        public ?TerminologyCapabilitiesSoftware $software = null,
        /** @var TerminologyCapabilitiesImplementation|null implementation If this describes a specific instance */
        public ?TerminologyCapabilitiesImplementation $implementation = null,
        /** @var bool|null lockedDate Whether lockedDate is supported */
        public ?bool $lockedDate = null,
        /** @var array<TerminologyCapabilitiesCodeSystem> codeSystem A code system supported by the server */
        public array $codeSystem = [],
        /** @var TerminologyCapabilitiesExpansion|null expansion Information about the [ValueSet/$expand](valueset-operation-expand.html) operation */
        public ?TerminologyCapabilitiesExpansion $expansion = null,
        /** @var CodeSearchSupportType|null codeSearch explicit | all */
        public ?CodeSearchSupportType $codeSearch = null,
        /** @var TerminologyCapabilitiesValidateCode|null validateCode Information about the [ValueSet/$validate-code](valueset-operation-validate-code.html) operation */
        public ?TerminologyCapabilitiesValidateCode $validateCode = null,
        /** @var TerminologyCapabilitiesTranslation|null translation Information about the [ConceptMap/$translate](conceptmap-operation-translate.html) operation */
        public ?TerminologyCapabilitiesTranslation $translation = null,
        /** @var TerminologyCapabilitiesClosure|null closure Information about the [ConceptMap/$closure](conceptmap-operation-closure.html) operation */
        public ?TerminologyCapabilitiesClosure $closure = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
