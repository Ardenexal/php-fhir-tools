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
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRUsageContext;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Quality Information)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Measure
 *
 * @description The Measure resource provides the definition of a quality measure.
 */
#[FhirResource(type: 'Measure', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Measure', fhirVersion: 'R5')]
class FHIRMeasure extends FHIRDomainResource
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
        /** @var FHIRUri|null url Canonical identifier for this measure, represented as a URI (globally unique) */
        public ?FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Additional identifier for the measure */
        public array $identifier = [],
        /** @var FHIRString|string|null version Business version of the measure */
        public FHIRString|string|null $version = null,
        /** @var FHIRString|string|FHIRCoding|null versionAlgorithmX How to compare versions */
        public FHIRString|string|FHIRCoding|null $versionAlgorithmX = null,
        /** @var FHIRString|string|null name Name for this measure (computer friendly) */
        public FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this measure (human friendly) */
        public FHIRString|string|null $title = null,
        /** @var FHIRString|string|null subtitle Subordinate title of the measure */
        public FHIRString|string|null $subtitle = null,
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?FHIRPublicationStatusType $status = null,
        /** @var FHIRBoolean|null experimental For testing purposes, not real usage */
        public ?FHIRBoolean $experimental = null,
        /** @var FHIRCodeableConcept|FHIRReference|null subjectX E.g. Patient, Practitioner, RelatedPerson, Organization, Location, Device */
        public FHIRCodeableConcept|FHIRReference|null $subjectX = null,
        /** @var FHIRFHIRTypesType|null basis Population basis */
        public ?FHIRFHIRTypesType $basis = null,
        /** @var FHIRDateTime|null date Date last changed */
        public ?FHIRDateTime $date = null,
        /** @var FHIRString|string|null publisher Name of the publisher/steward (organization or individual) */
        public FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var FHIRMarkdown|null description Natural language description of the measure */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for measure (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null purpose Why this measure is defined */
        public ?FHIRMarkdown $purpose = null,
        /** @var FHIRMarkdown|null usage Describes the clinical usage of the measure */
        public ?FHIRMarkdown $usage = null,
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions */
        public ?FHIRMarkdown $copyright = null,
        /** @var FHIRString|string|null copyrightLabel Copyright holder and year(s) */
        public FHIRString|string|null $copyrightLabel = null,
        /** @var FHIRDate|null approvalDate When the measure was approved by publisher */
        public ?FHIRDate $approvalDate = null,
        /** @var FHIRDate|null lastReviewDate When the measure was last reviewed by the publisher */
        public ?FHIRDate $lastReviewDate = null,
        /** @var FHIRPeriod|null effectivePeriod When the measure is expected to be used */
        public ?FHIRPeriod $effectivePeriod = null,
        /** @var array<FHIRCodeableConcept> topic The category of the measure, such as Education, Treatment, Assessment, etc */
        public array $topic = [],
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
        /** @var array<FHIRCanonical> library Logic used by the measure */
        public array $library = [],
        /** @var FHIRMarkdown|null disclaimer Disclaimer for use of the measure or its referenced content */
        public ?FHIRMarkdown $disclaimer = null,
        /** @var FHIRCodeableConcept|null scoring proportion | ratio | continuous-variable | cohort */
        public ?FHIRCodeableConcept $scoring = null,
        /** @var FHIRCodeableConcept|null scoringUnit What units? */
        public ?FHIRCodeableConcept $scoringUnit = null,
        /** @var FHIRCodeableConcept|null compositeScoring opportunity | all-or-nothing | linear | weighted */
        public ?FHIRCodeableConcept $compositeScoring = null,
        /** @var array<FHIRCodeableConcept> type process | outcome | structure | patient-reported-outcome | composite */
        public array $type = [],
        /** @var FHIRMarkdown|null riskAdjustment How risk adjustment is applied for this measure */
        public ?FHIRMarkdown $riskAdjustment = null,
        /** @var FHIRMarkdown|null rateAggregation How is rate aggregation performed for this measure */
        public ?FHIRMarkdown $rateAggregation = null,
        /** @var FHIRMarkdown|null rationale Detailed description of why the measure exists */
        public ?FHIRMarkdown $rationale = null,
        /** @var FHIRMarkdown|null clinicalRecommendationStatement Summary of clinical guidelines */
        public ?FHIRMarkdown $clinicalRecommendationStatement = null,
        /** @var FHIRCodeableConcept|null improvementNotation increase | decrease */
        public ?FHIRCodeableConcept $improvementNotation = null,
        /** @var array<FHIRMeasureTerm> term Defined terms used in the measure documentation */
        public array $term = [],
        /** @var FHIRMarkdown|null guidance Additional guidance for implementers (deprecated) */
        public ?FHIRMarkdown $guidance = null,
        /** @var array<FHIRMeasureGroup> group Population criteria group */
        public array $group = [],
        /** @var array<FHIRMeasureSupplementalData> supplementalData What other data should be reported with the measure */
        public array $supplementalData = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
