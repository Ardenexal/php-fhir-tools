<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactDetail;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMessageSignificanceCategoryType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMessageheaderResponseRequestType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRUsageContext;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
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
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/MessageDefinition',
    fhirVersion: 'R5',
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
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRUri|null url The cannonical URL for a given MessageDefinition */
        public ?FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Business Identifier for a given MessageDefinition */
        public array $identifier = [],
        /** @var FHIRString|string|null version Business version of the message definition */
        public FHIRString|string|null $version = null,
        /** @var FHIRString|string|FHIRCoding|null versionAlgorithmX How to compare versions */
        public FHIRString|string|FHIRCoding|null $versionAlgorithmX = null,
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
        /** @var FHIRString|string|null publisher Name of the publisher/steward (organization or individual) */
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
        /** @var FHIRString|string|null copyrightLabel Copyright holder and year(s) */
        public FHIRString|string|null $copyrightLabel = null,
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
        /** @var FHIRCanonical|null graph Canonical reference to a GraphDefinition */
        public ?FHIRCanonical $graph = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
