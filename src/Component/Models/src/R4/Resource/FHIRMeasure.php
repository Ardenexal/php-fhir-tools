<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Quality Information)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Measure
 *
 * @description The Measure resource provides the definition of a quality measure.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Measure', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Measure', fhirVersion: 'R4')]
class FHIRMeasure extends FHIRDomainResource
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
        /** @var FHIRUri|null url Canonical identifier for this measure, represented as a URI (globally unique) */
        public ?\FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Additional identifier for the measure */
        public array $identifier = [],
        /** @var FHIRString|string|null version Business version of the measure */
        public \FHIRString|string|null $version = null,
        /** @var FHIRString|string|null name Name for this measure (computer friendly) */
        public \FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this measure (human friendly) */
        public \FHIRString|string|null $title = null,
        /** @var FHIRString|string|null subtitle Subordinate title of the measure */
        public \FHIRString|string|null $subtitle = null,
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?\FHIRPublicationStatusType $status = null,
        /** @var FHIRBoolean|null experimental For testing purposes, not real usage */
        public ?\FHIRBoolean $experimental = null,
        /** @var FHIRCodeableConcept|FHIRReference|null subjectX E.g. Patient, Practitioner, RelatedPerson, Organization, Location, Device */
        public \FHIRCodeableConcept|\FHIRReference|null $subjectX = null,
        /** @var FHIRDateTime|null date Date last changed */
        public ?\FHIRDateTime $date = null,
        /** @var FHIRString|string|null publisher Name of the publisher (organization or individual) */
        public \FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var FHIRMarkdown|null description Natural language description of the measure */
        public ?\FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for measure (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null purpose Why this measure is defined */
        public ?\FHIRMarkdown $purpose = null,
        /** @var FHIRString|string|null usage Describes the clinical usage of the measure */
        public \FHIRString|string|null $usage = null,
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions */
        public ?\FHIRMarkdown $copyright = null,
        /** @var FHIRDate|null approvalDate When the measure was approved by publisher */
        public ?\FHIRDate $approvalDate = null,
        /** @var FHIRDate|null lastReviewDate When the measure was last reviewed */
        public ?\FHIRDate $lastReviewDate = null,
        /** @var FHIRPeriod|null effectivePeriod When the measure is expected to be used */
        public ?\FHIRPeriod $effectivePeriod = null,
        /** @var array<FHIRCodeableConcept> topic The category of the measure, such as Education, Treatment, Assessment, etc. */
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
        /** @var array<FHIRCanonical> library Logic used by the measure */
        public array $library = [],
        /** @var FHIRMarkdown|null disclaimer Disclaimer for use of the measure or its referenced content */
        public ?\FHIRMarkdown $disclaimer = null,
        /** @var FHIRCodeableConcept|null scoring proportion | ratio | continuous-variable | cohort */
        public ?\FHIRCodeableConcept $scoring = null,
        /** @var FHIRCodeableConcept|null compositeScoring opportunity | all-or-nothing | linear | weighted */
        public ?\FHIRCodeableConcept $compositeScoring = null,
        /** @var array<FHIRCodeableConcept> type process | outcome | structure | patient-reported-outcome | composite */
        public array $type = [],
        /** @var FHIRString|string|null riskAdjustment How risk adjustment is applied for this measure */
        public \FHIRString|string|null $riskAdjustment = null,
        /** @var FHIRString|string|null rateAggregation How is rate aggregation performed for this measure */
        public \FHIRString|string|null $rateAggregation = null,
        /** @var FHIRMarkdown|null rationale Detailed description of why the measure exists */
        public ?\FHIRMarkdown $rationale = null,
        /** @var FHIRMarkdown|null clinicalRecommendationStatement Summary of clinical guidelines */
        public ?\FHIRMarkdown $clinicalRecommendationStatement = null,
        /** @var FHIRCodeableConcept|null improvementNotation increase | decrease */
        public ?\FHIRCodeableConcept $improvementNotation = null,
        /** @var array<FHIRMarkdown> definition Defined terms used in the measure documentation */
        public array $definition = [],
        /** @var FHIRMarkdown|null guidance Additional guidance for implementers */
        public ?\FHIRMarkdown $guidance = null,
        /** @var array<FHIRMeasureGroup> group Population criteria group */
        public array $group = [],
        /** @var array<FHIRMeasureSupplementalData> supplementalData What other data should be reported with the measure */
        public array $supplementalData = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
