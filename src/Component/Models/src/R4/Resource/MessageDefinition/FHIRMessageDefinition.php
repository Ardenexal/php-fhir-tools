<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMessageSignificanceCategoryType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMessageheaderResponseRequestType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
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
class FHIRMessageDefinition extends FHIRDomainResource
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
        /** @var FHIRUri|null url Business Identifier for a given MessageDefinition */
        public ?FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Primary key for the message definition on a given server */
        public array $identifier = [],
        /** @var FHIRString|string|null version Business version of the message definition */
        public FHIRString|string|null $version = null,
        /** @var FHIRString|string|null name Name for this message definition (computer friendly) */
        public FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this message definition (human friendly) */
        public FHIRString|string|null $title = null,
        /** @var array<FHIRCanonical> replaces Takes the place of */
        public array $replaces = [],
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
        /** @var FHIRMarkdown|null description Natural language description of the message definition */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for message definition (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null purpose Why this message definition is defined */
        public ?FHIRMarkdown $purpose = null,
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions */
        public ?FHIRMarkdown $copyright = null,
        /** @var FHIRCanonical|null base Definition this one is based on */
        public ?FHIRCanonical $base = null,
        /** @var array<FHIRCanonical> parent Protocol/workflow this is part of */
        public array $parent = [],
        /** @var FHIRCoding|FHIRUri|null eventX Event code  or link to the EventDefinition */
        #[NotBlank]
        public FHIRCoding|FHIRUri|null $eventX = null,
        /** @var FHIRMessageSignificanceCategoryType|null category consequence | currency | notification */
        public ?FHIRMessageSignificanceCategoryType $category = null,
        /** @var array<FHIRMessageDefinitionFocus> focus Resource(s) that are the subject of the event */
        public array $focus = [],
        /** @var FHIRMessageheaderResponseRequestType|null responseRequired always | on-error | never | on-success */
        public ?FHIRMessageheaderResponseRequestType $responseRequired = null,
        /** @var array<FHIRMessageDefinitionAllowedResponse> allowedResponse Responses to this message */
        public array $allowedResponse = [],
        /** @var array<FHIRCanonical> graph Canonical reference to a GraphDefinition */
        public array $graph = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
