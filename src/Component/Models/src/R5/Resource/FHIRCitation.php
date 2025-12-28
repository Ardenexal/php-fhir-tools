<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Citation
 *
 * @description The Citation Resource enables reference to any knowledge artifact for purposes of identification and attribution. The Citation Resource supports existing reference structures and developing publication practices such as versioning, expressing complex contributorship roles, and referencing computable resources.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Citation', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Citation', fhirVersion: 'R5')]
class FHIRCitation extends FHIRDomainResource
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
        /** @var FHIRUri|null url Canonical identifier for this citation record, represented as a globally unique URI */
        public ?\FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Identifier for the citation record itself */
        public array $identifier = [],
        /** @var FHIRString|string|null version Business version of the citation record */
        public \FHIRString|string|null $version = null,
        /** @var FHIRString|string|FHIRCoding|null versionAlgorithmX How to compare versions */
        public \FHIRString|string|\FHIRCoding|null $versionAlgorithmX = null,
        /** @var FHIRString|string|null name Name for this citation record (computer friendly) */
        public \FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this citation record (human friendly) */
        public \FHIRString|string|null $title = null,
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?\FHIRPublicationStatusType $status = null,
        /** @var FHIRBoolean|null experimental For testing purposes, not real usage */
        public ?\FHIRBoolean $experimental = null,
        /** @var FHIRDateTime|null date Date last changed */
        public ?\FHIRDateTime $date = null,
        /** @var FHIRString|string|null publisher The publisher of the citation record, not the publisher of the article or artifact being cited */
        public \FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher of the citation record */
        public array $contact = [],
        /** @var FHIRMarkdown|null description Natural language description of the citation */
        public ?\FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext The context that the citation record content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for citation record (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null purpose Why this citation is defined */
        public ?\FHIRMarkdown $purpose = null,
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions for the citation record, not for the cited artifact */
        public ?\FHIRMarkdown $copyright = null,
        /** @var FHIRString|string|null copyrightLabel Copyright holder and year(s) for the ciation record, not for the cited artifact */
        public \FHIRString|string|null $copyrightLabel = null,
        /** @var FHIRDate|null approvalDate When the citation record was approved by publisher */
        public ?\FHIRDate $approvalDate = null,
        /** @var FHIRDate|null lastReviewDate When the citation record was last reviewed by the publisher */
        public ?\FHIRDate $lastReviewDate = null,
        /** @var FHIRPeriod|null effectivePeriod When the citation record is expected to be used */
        public ?\FHIRPeriod $effectivePeriod = null,
        /** @var array<FHIRContactDetail> author Who authored the citation record */
        public array $author = [],
        /** @var array<FHIRContactDetail> editor Who edited the citation record */
        public array $editor = [],
        /** @var array<FHIRContactDetail> reviewer Who reviewed the citation record */
        public array $reviewer = [],
        /** @var array<FHIRContactDetail> endorser Who endorsed the citation record */
        public array $endorser = [],
        /** @var array<FHIRCitationSummary> summary A human-readable display of key concepts to represent the citation */
        public array $summary = [],
        /** @var array<FHIRCitationClassification> classification The assignment to an organizing scheme */
        public array $classification = [],
        /** @var array<FHIRAnnotation> note Used for general notes and annotations not coded elsewhere */
        public array $note = [],
        /** @var array<FHIRCodeableConcept> currentState The status of the citation record */
        public array $currentState = [],
        /** @var array<FHIRCitationStatusDate> statusDate An effective date or period for a status of the citation record */
        public array $statusDate = [],
        /** @var array<FHIRRelatedArtifact> relatedArtifact Artifact related to the citation record */
        public array $relatedArtifact = [],
        /** @var FHIRCitationCitedArtifact|null citedArtifact The article or artifact being described */
        public ?\FHIRCitationCitedArtifact $citedArtifact = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
