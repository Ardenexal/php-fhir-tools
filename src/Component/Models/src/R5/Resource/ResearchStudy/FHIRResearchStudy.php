<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ResearchStudy
 *
 * @description A scientific study of nature that sometimes includes processes involved in health and disease. For example, clinical trials are research studies that involve people. These studies may be related to new ways to screen, prevent, diagnose, and treat disease. They may also study certain outcomes and certain groups of people by looking at data collected in the past or future.
 */
#[FhirResource(type: 'ResearchStudy', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/ResearchStudy', fhirVersion: 'R5')]
class FHIRResearchStudy extends FHIRDomainResource
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
        /** @var FHIRUri|null url Canonical identifier for this study resource */
        public ?FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Business Identifier for study */
        public array $identifier = [],
        /** @var FHIRString|string|null version The business version for the study record */
        public FHIRString|string|null $version = null,
        /** @var FHIRString|string|null name Name for this study (computer friendly) */
        public FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Human readable name of the study */
        public FHIRString|string|null $title = null,
        /** @var array<FHIRResearchStudyLabel> label Additional names for the study */
        public array $label = [],
        /** @var array<FHIRReference> protocol Steps followed in executing study */
        public array $protocol = [],
        /** @var array<FHIRReference> partOf Part of larger study */
        public array $partOf = [],
        /** @var array<FHIRRelatedArtifact> relatedArtifact References, URLs, and attachments */
        public array $relatedArtifact = [],
        /** @var FHIRDateTime|null date Date the resource last changed */
        public ?FHIRDateTime $date = null,
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?FHIRPublicationStatusType $status = null,
        /** @var FHIRCodeableConcept|null primaryPurposeType treatment | prevention | diagnostic | supportive-care | screening | health-services-research | basic-science | device-feasibility */
        public ?FHIRCodeableConcept $primaryPurposeType = null,
        /** @var FHIRCodeableConcept|null phase n-a | early-phase-1 | phase-1 | phase-1-phase-2 | phase-2 | phase-2-phase-3 | phase-3 | phase-4 */
        public ?FHIRCodeableConcept $phase = null,
        /** @var array<FHIRCodeableConcept> studyDesign Classifications of the study design characteristics */
        public array $studyDesign = [],
        /** @var array<FHIRCodeableReference> focus Drugs, devices, etc. under study */
        public array $focus = [],
        /** @var array<FHIRCodeableConcept> condition Condition being studied */
        public array $condition = [],
        /** @var array<FHIRCodeableConcept> keyword Used to search for the study */
        public array $keyword = [],
        /** @var array<FHIRCodeableConcept> region Geographic area for the study */
        public array $region = [],
        /** @var FHIRMarkdown|null descriptionSummary Brief text explaining the study */
        public ?FHIRMarkdown $descriptionSummary = null,
        /** @var FHIRMarkdown|null description Detailed narrative of the study */
        public ?FHIRMarkdown $description = null,
        /** @var FHIRPeriod|null period When the study began and ended */
        public ?FHIRPeriod $period = null,
        /** @var array<FHIRReference> site Facility where study activities are conducted */
        public array $site = [],
        /** @var array<FHIRAnnotation> note Comments made about the study */
        public array $note = [],
        /** @var array<FHIRCodeableConcept> classifier Classification for the study */
        public array $classifier = [],
        /** @var array<FHIRResearchStudyAssociatedParty> associatedParty Sponsors, collaborators, and other parties */
        public array $associatedParty = [],
        /** @var array<FHIRResearchStudyProgressStatus> progressStatus Status of study with time for that status */
        public array $progressStatus = [],
        /** @var FHIRCodeableConcept|null whyStopped accrual-goal-met | closed-due-to-toxicity | closed-due-to-lack-of-study-progress | temporarily-closed-per-study-design */
        public ?FHIRCodeableConcept $whyStopped = null,
        /** @var FHIRResearchStudyRecruitment|null recruitment Target or actual group of participants enrolled in study */
        public ?FHIRResearchStudyRecruitment $recruitment = null,
        /** @var array<FHIRResearchStudyComparisonGroup> comparisonGroup Defined path through the study for a subject */
        public array $comparisonGroup = [],
        /** @var array<FHIRResearchStudyObjective> objective A goal for the study */
        public array $objective = [],
        /** @var array<FHIRResearchStudyOutcomeMeasure> outcomeMeasure A variable measured during the study */
        public array $outcomeMeasure = [],
        /** @var array<FHIRReference> result Link to results generated during the study */
        public array $result = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
