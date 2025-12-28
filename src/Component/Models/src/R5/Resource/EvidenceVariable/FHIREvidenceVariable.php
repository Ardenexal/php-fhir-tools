<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 *
 * @see http://hl7.org/fhir/StructureDefinition/EvidenceVariable
 *
 * @description The EvidenceVariable resource describes an element that knowledge (Evidence) is about.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'EvidenceVariable',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/EvidenceVariable',
    fhirVersion: 'R5',
)]
class FHIREvidenceVariable extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?\FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRUri|null url Canonical identifier for this evidence variable, represented as a URI (globally unique) */
        public ?\FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Additional identifier for the evidence variable */
        public array $identifier = [],
        /** @var FHIRString|string|null version Business version of the evidence variable */
        public \FHIRString|string|null $version = null,
        /** @var FHIRString|string|FHIRCoding|null versionAlgorithmX How to compare versions */
        public \FHIRString|string|\FHIRCoding|null $versionAlgorithmX = null,
        /** @var FHIRString|string|null name Name for this evidence variable (computer friendly) */
        public \FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this evidence variable (human friendly) */
        public \FHIRString|string|null $title = null,
        /** @var FHIRString|string|null shortTitle Title for use in informal contexts */
        public \FHIRString|string|null $shortTitle = null,
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?\FHIRPublicationStatusType $status = null,
        /** @var FHIRBoolean|null experimental For testing purposes, not real usage */
        public ?\FHIRBoolean $experimental = null,
        /** @var FHIRDateTime|null date Date last changed */
        public ?\FHIRDateTime $date = null,
        /** @var FHIRString|string|null publisher Name of the publisher/steward (organization or individual) */
        public \FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var FHIRMarkdown|null description Natural language description of the evidence variable */
        public ?\FHIRMarkdown $description = null,
        /** @var array<FHIRAnnotation> note Used for footnotes or explanatory notes */
        public array $note = [],
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var FHIRMarkdown|null purpose Why this EvidenceVariable is defined */
        public ?\FHIRMarkdown $purpose = null,
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions */
        public ?\FHIRMarkdown $copyright = null,
        /** @var FHIRString|string|null copyrightLabel Copyright holder and year(s) */
        public \FHIRString|string|null $copyrightLabel = null,
        /** @var FHIRDate|null approvalDate When the resource was approved by publisher */
        public ?\FHIRDate $approvalDate = null,
        /** @var FHIRDate|null lastReviewDate When the resource was last reviewed by the publisher */
        public ?\FHIRDate $lastReviewDate = null,
        /** @var FHIRPeriod|null effectivePeriod When the resource is expected to be used */
        public ?\FHIRPeriod $effectivePeriod = null,
        /** @var array<FHIRContactDetail> author Who authored the content */
        public array $author = [],
        /** @var array<FHIRContactDetail> editor Who edited the content */
        public array $editor = [],
        /** @var array<FHIRContactDetail> reviewer Who reviewed the content */
        public array $reviewer = [],
        /** @var array<FHIRContactDetail> endorser Who endorsed the content */
        public array $endorser = [],
        /** @var array<FHIRRelatedArtifact> relatedArtifact Additional documentation, citations, etc */
        public array $relatedArtifact = [],
        /** @var FHIRBoolean|null actual Actual or conceptual */
        public ?\FHIRBoolean $actual = null,
        /** @var array<FHIREvidenceVariableCharacteristic> characteristic A defining factor of the EvidenceVariable */
        public array $characteristic = [],
        /** @var FHIREvidenceVariableHandlingType|null handling continuous | dichotomous | ordinal | polychotomous */
        public ?\FHIREvidenceVariableHandlingType $handling = null,
        /** @var array<FHIREvidenceVariableCategory> category A grouping for ordinal or polychotomous variables */
        public array $category = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
