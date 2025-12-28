<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 *
 * @see http://hl7.org/fhir/StructureDefinition/EvidenceReport
 *
 * @description The EvidenceReport Resource is a specialized container for a collection of resources and codable concepts, adapted to support compositions of Evidence, EvidenceVariable, and Citation resources and related concepts.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'EvidenceReport',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/EvidenceReport',
    fhirVersion: 'R4B',
)]
class FHIREvidenceReport extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRUri|null url Canonical identifier for this EvidenceReport, represented as a globally unique URI */
        public ?\FHIRUri $url = null,
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?\FHIRPublicationStatusType $status = null,
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRIdentifier> identifier Unique identifier for the evidence report */
        public array $identifier = [],
        /** @var array<FHIRIdentifier> relatedIdentifier Identifiers for articles that may relate to more than one evidence report */
        public array $relatedIdentifier = [],
        /** @var FHIRReference|FHIRMarkdown|null citeAsX Citation for this report */
        public \FHIRReference|\FHIRMarkdown|null $citeAsX = null,
        /** @var FHIRCodeableConcept|null type Kind of report */
        public ?\FHIRCodeableConcept $type = null,
        /** @var array<FHIRAnnotation> note Used for footnotes and annotations */
        public array $note = [],
        /** @var array<FHIRRelatedArtifact> relatedArtifact Link, description or reference to artifact associated with the report */
        public array $relatedArtifact = [],
        /** @var FHIREvidenceReportSubject|null subject Focus of the report */
        #[NotBlank]
        public ?\FHIREvidenceReportSubject $subject = null,
        /** @var FHIRString|string|null publisher Name of the publisher (organization or individual) */
        public \FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var array<FHIRContactDetail> author Who authored the content */
        public array $author = [],
        /** @var array<FHIRContactDetail> editor Who edited the content */
        public array $editor = [],
        /** @var array<FHIRContactDetail> reviewer Who reviewed the content */
        public array $reviewer = [],
        /** @var array<FHIRContactDetail> endorser Who endorsed the content */
        public array $endorser = [],
        /** @var array<FHIREvidenceReportRelatesTo> relatesTo Relationships to other compositions/documents */
        public array $relatesTo = [],
        /** @var array<FHIREvidenceReportSection> section Composition is broken into sections */
        public array $section = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
