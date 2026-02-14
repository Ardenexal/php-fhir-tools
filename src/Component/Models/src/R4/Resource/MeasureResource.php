<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Measure\MeasureGroup;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Measure\MeasureSupplementalData;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Quality Information)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Measure
 *
 * @description The Measure resource provides the definition of a quality measure.
 */
#[FhirResource(type: 'Measure', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Measure', fhirVersion: 'R4')]
class MeasureResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var UriPrimitive|null url Canonical identifier for this measure, represented as a URI (globally unique) */
        public ?UriPrimitive $url = null,
        /** @var array<Identifier> identifier Additional identifier for the measure */
        public array $identifier = [],
        /** @var StringPrimitive|string|null version Business version of the measure */
        public StringPrimitive|string|null $version = null,
        /** @var StringPrimitive|string|null name Name for this measure (computer friendly) */
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null title Name for this measure (human friendly) */
        public StringPrimitive|string|null $title = null,
        /** @var StringPrimitive|string|null subtitle Subordinate title of the measure */
        public StringPrimitive|string|null $subtitle = null,
        /** @var PublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?PublicationStatusType $status = null,
        /** @var bool|null experimental For testing purposes, not real usage */
        public ?bool $experimental = null,
        /** @var CodeableConcept|Reference|null subjectX E.g. Patient, Practitioner, RelatedPerson, Organization, Location, Device */
        public CodeableConcept|Reference|null $subjectX = null,
        /** @var DateTimePrimitive|null date Date last changed */
        public ?DateTimePrimitive $date = null,
        /** @var StringPrimitive|string|null publisher Name of the publisher (organization or individual) */
        public StringPrimitive|string|null $publisher = null,
        /** @var array<ContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var MarkdownPrimitive|null description Natural language description of the measure */
        public ?MarkdownPrimitive $description = null,
        /** @var array<UsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<CodeableConcept> jurisdiction Intended jurisdiction for measure (if applicable) */
        public array $jurisdiction = [],
        /** @var MarkdownPrimitive|null purpose Why this measure is defined */
        public ?MarkdownPrimitive $purpose = null,
        /** @var StringPrimitive|string|null usage Describes the clinical usage of the measure */
        public StringPrimitive|string|null $usage = null,
        /** @var MarkdownPrimitive|null copyright Use and/or publishing restrictions */
        public ?MarkdownPrimitive $copyright = null,
        /** @var DatePrimitive|null approvalDate When the measure was approved by publisher */
        public ?DatePrimitive $approvalDate = null,
        /** @var DatePrimitive|null lastReviewDate When the measure was last reviewed */
        public ?DatePrimitive $lastReviewDate = null,
        /** @var Period|null effectivePeriod When the measure is expected to be used */
        public ?Period $effectivePeriod = null,
        /** @var array<CodeableConcept> topic The category of the measure, such as Education, Treatment, Assessment, etc. */
        public array $topic = [],
        /** @var array<ContactDetail> author Who authored the content */
        public array $author = [],
        /** @var array<ContactDetail> editor Who edited the content */
        public array $editor = [],
        /** @var array<ContactDetail> reviewer Who reviewed the content */
        public array $reviewer = [],
        /** @var array<ContactDetail> endorser Who endorsed the content */
        public array $endorser = [],
        /** @var array<RelatedArtifact> relatedArtifact Additional documentation, citations, etc. */
        public array $relatedArtifact = [],
        /** @var array<CanonicalPrimitive> library Logic used by the measure */
        public array $library = [],
        /** @var MarkdownPrimitive|null disclaimer Disclaimer for use of the measure or its referenced content */
        public ?MarkdownPrimitive $disclaimer = null,
        /** @var CodeableConcept|null scoring proportion | ratio | continuous-variable | cohort */
        public ?CodeableConcept $scoring = null,
        /** @var CodeableConcept|null compositeScoring opportunity | all-or-nothing | linear | weighted */
        public ?CodeableConcept $compositeScoring = null,
        /** @var array<CodeableConcept> type process | outcome | structure | patient-reported-outcome | composite */
        public array $type = [],
        /** @var StringPrimitive|string|null riskAdjustment How risk adjustment is applied for this measure */
        public StringPrimitive|string|null $riskAdjustment = null,
        /** @var StringPrimitive|string|null rateAggregation How is rate aggregation performed for this measure */
        public StringPrimitive|string|null $rateAggregation = null,
        /** @var MarkdownPrimitive|null rationale Detailed description of why the measure exists */
        public ?MarkdownPrimitive $rationale = null,
        /** @var MarkdownPrimitive|null clinicalRecommendationStatement Summary of clinical guidelines */
        public ?MarkdownPrimitive $clinicalRecommendationStatement = null,
        /** @var CodeableConcept|null improvementNotation increase | decrease */
        public ?CodeableConcept $improvementNotation = null,
        /** @var array<MarkdownPrimitive> definition Defined terms used in the measure documentation */
        public array $definition = [],
        /** @var MarkdownPrimitive|null guidance Additional guidance for implementers */
        public ?MarkdownPrimitive $guidance = null,
        /** @var array<MeasureGroup> group Population criteria group */
        public array $group = [],
        /** @var array<MeasureSupplementalData> supplementalData What other data should be reported with the measure */
        public array $supplementalData = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
