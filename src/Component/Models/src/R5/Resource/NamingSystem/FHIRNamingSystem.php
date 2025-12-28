<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactDetail;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRUsageContext;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Terminology Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/NamingSystem
 *
 * @description A curated namespace that issues unique symbols within that namespace for the identification of concepts, people, devices, etc.  Represents a "System" used within the Identifier and Coding data types.
 */
#[FhirResource(type: 'NamingSystem', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/NamingSystem', fhirVersion: 'R5')]
class FHIRNamingSystem extends FHIRDomainResource
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
        /** @var FHIRUri|null url Canonical identifier for this naming system, represented as a URI (globally unique) */
        public ?FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Additional identifier for the naming system (business identifier) */
        public array $identifier = [],
        /** @var FHIRString|string|null version Business version of the naming system */
        public FHIRString|string|null $version = null,
        /** @var FHIRString|string|FHIRCoding|null versionAlgorithmX How to compare versions */
        public FHIRString|string|FHIRCoding|null $versionAlgorithmX = null,
        /** @var FHIRString|string|null name Name for this naming system (computer friendly) */
        #[NotBlank]
        public FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Title for this naming system (human friendly) */
        public FHIRString|string|null $title = null,
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?FHIRPublicationStatusType $status = null,
        /** @var FHIRNamingSystemTypeType|null kind codesystem | identifier | root */
        #[NotBlank]
        public ?FHIRNamingSystemTypeType $kind = null,
        /** @var FHIRBoolean|null experimental For testing purposes, not real usage */
        public ?FHIRBoolean $experimental = null,
        /** @var FHIRDateTime|null date Date last changed */
        #[NotBlank]
        public ?FHIRDateTime $date = null,
        /** @var FHIRString|string|null publisher Name of the publisher/steward (organization or individual) */
        public FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var FHIRString|string|null responsible Who maintains system namespace? */
        public FHIRString|string|null $responsible = null,
        /** @var FHIRCodeableConcept|null type e.g. driver,  provider,  patient, bank etc */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRMarkdown|null description Natural language description of the naming system */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for naming system (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null purpose Why this naming system is defined */
        public ?FHIRMarkdown $purpose = null,
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions */
        public ?FHIRMarkdown $copyright = null,
        /** @var FHIRString|string|null copyrightLabel Copyright holder and year(s) */
        public FHIRString|string|null $copyrightLabel = null,
        /** @var FHIRDate|null approvalDate When the NamingSystem was approved by publisher */
        public ?FHIRDate $approvalDate = null,
        /** @var FHIRDate|null lastReviewDate When the NamingSystem was last reviewed by the publisher */
        public ?FHIRDate $lastReviewDate = null,
        /** @var FHIRPeriod|null effectivePeriod When the NamingSystem is expected to be used */
        public ?FHIRPeriod $effectivePeriod = null,
        /** @var array<FHIRCodeableConcept> topic E.g. Education, Treatment, Assessment, etc */
        public array $topic = [],
        /** @var array<FHIRContactDetail> author Who authored the CodeSystem */
        public array $author = [],
        /** @var array<FHIRContactDetail> editor Who edited the NamingSystem */
        public array $editor = [],
        /** @var array<FHIRContactDetail> reviewer Who reviewed the NamingSystem */
        public array $reviewer = [],
        /** @var array<FHIRContactDetail> endorser Who endorsed the NamingSystem */
        public array $endorser = [],
        /** @var array<FHIRRelatedArtifact> relatedArtifact Additional documentation, citations, etc */
        public array $relatedArtifact = [],
        /** @var FHIRString|string|null usage How/where is it used */
        public FHIRString|string|null $usage = null,
        /** @var array<FHIRNamingSystemUniqueId> uniqueId Unique identifiers used for system */
        public array $uniqueId = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
