<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCapabilityStatementKindType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeSearchSupportType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactDetail;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRUsageContext;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Terminology Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/TerminologyCapabilities
 *
 * @description A TerminologyCapabilities resource documents a set of capabilities (behaviors) of a FHIR Terminology Server that may be used as a statement of actual server functionality or a statement of required or desired server implementation.
 */
#[FhirResource(
    type: 'TerminologyCapabilities',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/TerminologyCapabilities',
    fhirVersion: 'R5',
)]
class FHIRTerminologyCapabilities extends FHIRDomainResource
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
        /** @var FHIRUri|null url Canonical identifier for this terminology capabilities, represented as a URI (globally unique) */
        public ?FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Additional identifier for the terminology capabilities */
        public array $identifier = [],
        /** @var FHIRString|string|null version Business version of the terminology capabilities */
        public FHIRString|string|null $version = null,
        /** @var FHIRString|string|FHIRCoding|null versionAlgorithmX How to compare versions */
        public FHIRString|string|FHIRCoding|null $versionAlgorithmX = null,
        /** @var FHIRString|string|null name Name for this terminology capabilities (computer friendly) */
        public FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this terminology capabilities (human friendly) */
        public FHIRString|string|null $title = null,
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
        /** @var FHIRMarkdown|null description Natural language description of the terminology capabilities */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for terminology capabilities (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null purpose Why this terminology capabilities is defined */
        public ?FHIRMarkdown $purpose = null,
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions */
        public ?FHIRMarkdown $copyright = null,
        /** @var FHIRString|string|null copyrightLabel Copyright holder and year(s) */
        public FHIRString|string|null $copyrightLabel = null,
        /** @var FHIRCapabilityStatementKindType|null kind instance | capability | requirements */
        #[NotBlank]
        public ?FHIRCapabilityStatementKindType $kind = null,
        /** @var FHIRTerminologyCapabilitiesSoftware|null software Software that is covered by this terminology capability statement */
        public ?FHIRTerminologyCapabilitiesSoftware $software = null,
        /** @var FHIRTerminologyCapabilitiesImplementation|null implementation If this describes a specific instance */
        public ?FHIRTerminologyCapabilitiesImplementation $implementation = null,
        /** @var FHIRBoolean|null lockedDate Whether lockedDate is supported */
        public ?FHIRBoolean $lockedDate = null,
        /** @var array<FHIRTerminologyCapabilitiesCodeSystem> codeSystem A code system supported by the server */
        public array $codeSystem = [],
        /** @var FHIRTerminologyCapabilitiesExpansion|null expansion Information about the [ValueSet/$expand](valueset-operation-expand.html) operation */
        public ?FHIRTerminologyCapabilitiesExpansion $expansion = null,
        /** @var FHIRCodeSearchSupportType|null codeSearch in-compose | in-expansion | in-compose-or-expansion */
        public ?FHIRCodeSearchSupportType $codeSearch = null,
        /** @var FHIRTerminologyCapabilitiesValidateCode|null validateCode Information about the [ValueSet/$validate-code](valueset-operation-validate-code.html) operation */
        public ?FHIRTerminologyCapabilitiesValidateCode $validateCode = null,
        /** @var FHIRTerminologyCapabilitiesTranslation|null translation Information about the [ConceptMap/$translate](conceptmap-operation-translate.html) operation */
        public ?FHIRTerminologyCapabilitiesTranslation $translation = null,
        /** @var FHIRTerminologyCapabilitiesClosure|null closure Information about the [ConceptMap/$closure](conceptmap-operation-closure.html) operation */
        public ?FHIRTerminologyCapabilitiesClosure $closure = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
