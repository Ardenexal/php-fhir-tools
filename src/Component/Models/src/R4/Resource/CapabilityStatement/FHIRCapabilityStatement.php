<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCapabilityStatementKindType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRFHIRVersionType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMimeTypesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRUsageContext;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
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
class FHIRCapabilityStatement extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRUri|null url Canonical identifier for this capability statement, represented as a URI (globally unique) */
        public ?FHIRUri $url = null,
        /** @var FHIRString|string|null version Business version of the capability statement */
        public FHIRString|string|null $version = null,
        /** @var FHIRString|string|null name Name for this capability statement (computer friendly) */
        public FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this capability statement (human friendly) */
        public FHIRString|string|null $title = null,
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?FHIRPublicationStatusType $status = null,
        /** @var FHIRBoolean|null experimental For testing purposes, not real usage */
        public ?FHIRBoolean $experimental = null,
        /** @var FHIRDateTime|null date Date last changed */
        #[NotBlank]
        public ?FHIRDateTime $date = null,
        /** @var FHIRString|string|null publisher Name of the publisher (organization or individual) */
        public FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var FHIRMarkdown|null description Natural language description of the capability statement */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for capability statement (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null purpose Why this capability statement is defined */
        public ?FHIRMarkdown $purpose = null,
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions */
        public ?FHIRMarkdown $copyright = null,
        /** @var FHIRCapabilityStatementKindType|null kind instance | capability | requirements */
        #[NotBlank]
        public ?FHIRCapabilityStatementKindType $kind = null,
        /** @var array<FHIRCanonical> instantiates Canonical URL of another capability statement this implements */
        public array $instantiates = [],
        /** @var array<FHIRCanonical> imports Canonical URL of another capability statement this adds to */
        public array $imports = [],
        /** @var FHIRCapabilityStatementSoftware|null software Software that is covered by this capability statement */
        public ?FHIRCapabilityStatementSoftware $software = null,
        /** @var FHIRCapabilityStatementImplementation|null implementation If this describes a specific instance */
        public ?FHIRCapabilityStatementImplementation $implementation = null,
        /** @var FHIRFHIRVersionType|null fhirVersion FHIR Version the system supports */
        #[NotBlank]
        public ?FHIRFHIRVersionType $fhirVersion = null,
        /** @var array<FHIRMimeTypesType> format formats supported (xml | json | ttl | mime type) */
        public array $format = [],
        /** @var array<FHIRMimeTypesType> patchFormat Patch formats supported */
        public array $patchFormat = [],
        /** @var array<FHIRCanonical> implementationGuide Implementation guides supported */
        public array $implementationGuide = [],
        /** @var array<FHIRCapabilityStatementRest> rest If the endpoint is a RESTful one */
        public array $rest = [],
        /** @var array<FHIRCapabilityStatementMessaging> messaging If messaging is supported */
        public array $messaging = [],
        /** @var array<FHIRCapabilityStatementDocument> document Document definition */
        public array $document = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
