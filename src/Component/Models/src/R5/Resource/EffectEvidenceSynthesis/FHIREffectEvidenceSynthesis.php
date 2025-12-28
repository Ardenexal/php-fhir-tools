<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactDetail;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRUsageContext;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 *
 * @see http://hl7.org/fhir/StructureDefinition/EffectEvidenceSynthesis
 *
 * @description The EffectEvidenceSynthesis resource describes the difference in an outcome between exposures states in a population where the effect estimate is derived from a combination of research studies.
 */
#[FhirResource(
    type: 'EffectEvidenceSynthesis',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/EffectEvidenceSynthesis',
    fhirVersion: 'R5',
)]
class FHIREffectEvidenceSynthesis extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRUri|null url Canonical identifier for this effect evidence synthesis, represented as a URI (globally unique) */
        public ?FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Additional identifier for the effect evidence synthesis */
        public array $identifier = [],
        /** @var FHIRString|string|null version Business version of the effect evidence synthesis */
        public FHIRString|string|null $version = null,
        /** @var FHIRString|string|null name Name for this effect evidence synthesis (computer friendly) */
        public FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this effect evidence synthesis (human friendly) */
        public FHIRString|string|null $title = null,
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?FHIRPublicationStatusType $status = null,
        /** @var FHIRDateTime|null date Date last changed */
        public ?FHIRDateTime $date = null,
        /** @var FHIRString|string|null publisher Name of the publisher (organization or individual) */
        public FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var FHIRMarkdown|null description Natural language description of the effect evidence synthesis */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRAnnotation> note Used for footnotes or explanatory notes */
        public array $note = [],
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for effect evidence synthesis (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions */
        public ?FHIRMarkdown $copyright = null,
        /** @var FHIRDate|null approvalDate When the effect evidence synthesis was approved by publisher */
        public ?FHIRDate $approvalDate = null,
        /** @var FHIRDate|null lastReviewDate When the effect evidence synthesis was last reviewed */
        public ?FHIRDate $lastReviewDate = null,
        /** @var FHIRPeriod|null effectivePeriod When the effect evidence synthesis is expected to be used */
        public ?FHIRPeriod $effectivePeriod = null,
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
        public ?FHIRCodeableConcept $synthesisType = null,
        /** @var FHIRCodeableConcept|null studyType Type of study */
        public ?FHIRCodeableConcept $studyType = null,
        /** @var FHIRReference|null population What population? */
        #[NotBlank]
        public ?FHIRReference $population = null,
        /** @var FHIRReference|null exposure What exposure? */
        #[NotBlank]
        public ?FHIRReference $exposure = null,
        /** @var FHIRReference|null exposureAlternative What comparison exposure? */
        #[NotBlank]
        public ?FHIRReference $exposureAlternative = null,
        /** @var FHIRReference|null outcome What outcome? */
        #[NotBlank]
        public ?FHIRReference $outcome = null,
        /** @var FHIREffectEvidenceSynthesisSampleSize|null sampleSize What sample size was involved? */
        public ?FHIREffectEvidenceSynthesisSampleSize $sampleSize = null,
        /** @var array<FHIREffectEvidenceSynthesisResultsByExposure> resultsByExposure What was the result per exposure? */
        public array $resultsByExposure = [],
        /** @var array<FHIREffectEvidenceSynthesisEffectEstimate> effectEstimate What was the estimated effect */
        public array $effectEstimate = [],
        /** @var array<FHIREffectEvidenceSynthesisCertainty> certainty How certain is the effect */
        public array $certainty = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
