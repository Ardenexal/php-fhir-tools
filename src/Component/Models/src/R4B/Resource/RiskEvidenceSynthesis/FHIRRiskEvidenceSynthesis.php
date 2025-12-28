<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 *
 * @see http://hl7.org/fhir/StructureDefinition/RiskEvidenceSynthesis
 *
 * @description The RiskEvidenceSynthesis resource describes the likelihood of an outcome in a population plus exposure state where the risk estimate is derived from a combination of research studies.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'RiskEvidenceSynthesis',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/RiskEvidenceSynthesis',
    fhirVersion: 'R4B',
)]
class FHIRRiskEvidenceSynthesis extends FHIRDomainResource
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
        /** @var FHIRUri|null url Canonical identifier for this risk evidence synthesis, represented as a URI (globally unique) */
        public ?\FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Additional identifier for the risk evidence synthesis */
        public array $identifier = [],
        /** @var FHIRString|string|null version Business version of the risk evidence synthesis */
        public \FHIRString|string|null $version = null,
        /** @var FHIRString|string|null name Name for this risk evidence synthesis (computer friendly) */
        public \FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this risk evidence synthesis (human friendly) */
        public \FHIRString|string|null $title = null,
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?\FHIRPublicationStatusType $status = null,
        /** @var FHIRDateTime|null date Date last changed */
        public ?\FHIRDateTime $date = null,
        /** @var FHIRString|string|null publisher Name of the publisher (organization or individual) */
        public \FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var FHIRMarkdown|null description Natural language description of the risk evidence synthesis */
        public ?\FHIRMarkdown $description = null,
        /** @var array<FHIRAnnotation> note Used for footnotes or explanatory notes */
        public array $note = [],
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for risk evidence synthesis (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions */
        public ?\FHIRMarkdown $copyright = null,
        /** @var FHIRDate|null approvalDate When the risk evidence synthesis was approved by publisher */
        public ?\FHIRDate $approvalDate = null,
        /** @var FHIRDate|null lastReviewDate When the risk evidence synthesis was last reviewed */
        public ?\FHIRDate $lastReviewDate = null,
        /** @var FHIRPeriod|null effectivePeriod When the risk evidence synthesis is expected to be used */
        public ?\FHIRPeriod $effectivePeriod = null,
        /** @var array<FHIRCodeableConcept> topic The category of the EffectEvidenceSynthesis, such as Education, Treatment, Assessment, etc. */
        public array $topic = [],
        /** @var array<FHIRContactDetail> author Who authored the content */
        public array $author = [],
        /** @var array<FHIRContactDetail> editor Who edited the content */
        public array $editor = [],
        /** @var array<FHIRContactDetail> reviewer Who reviewed the content */
        public array $reviewer = [],
        /** @var array<FHIRContactDetail> endorser Who endorsed the content */
        public array $endorser = [],
        /** @var array<FHIRRelatedArtifact> relatedArtifact Additional documentation, citations, etc. */
        public array $relatedArtifact = [],
        /** @var FHIRCodeableConcept|null synthesisType Type of synthesis */
        public ?\FHIRCodeableConcept $synthesisType = null,
        /** @var FHIRCodeableConcept|null studyType Type of study */
        public ?\FHIRCodeableConcept $studyType = null,
        /** @var FHIRReference|null population What population? */
        #[NotBlank]
        public ?\FHIRReference $population = null,
        /** @var FHIRReference|null exposure What exposure? */
        public ?\FHIRReference $exposure = null,
        /** @var FHIRReference|null outcome What outcome? */
        #[NotBlank]
        public ?\FHIRReference $outcome = null,
        /** @var FHIRRiskEvidenceSynthesisSampleSize|null sampleSize What sample size was involved? */
        public ?\FHIRRiskEvidenceSynthesisSampleSize $sampleSize = null,
        /** @var FHIRRiskEvidenceSynthesisRiskEstimate|null riskEstimate What was the estimated risk */
        public ?\FHIRRiskEvidenceSynthesisRiskEstimate $riskEstimate = null,
        /** @var array<FHIRRiskEvidenceSynthesisCertainty> certainty How certain is the risk */
        public array $certainty = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
