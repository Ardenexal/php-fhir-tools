<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ResearchStudy
 *
 * @description A process where a researcher or organization plans and then executes a series of steps intended to increase the field of healthcare-related knowledge.  This includes studies of safety, efficacy, comparative effectiveness and other information about medications, devices, therapies and other interventional and investigative techniques.  A ResearchStudy involves the gathering of information about human or animal subjects.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'ResearchStudy',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/ResearchStudy',
    fhirVersion: 'R4B',
)]
class FHIRResearchStudy extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Business Identifier for study */
        public array $identifier = [],
        /** @var FHIRString|string|null title Name for this study */
        public \FHIRString|string|null $title = null,
        /** @var array<FHIRReference> protocol Steps followed in executing study */
        public array $protocol = [],
        /** @var array<FHIRReference> partOf Part of larger study */
        public array $partOf = [],
        /** @var FHIRResearchStudyStatusType|null status active | administratively-completed | approved | closed-to-accrual | closed-to-accrual-and-intervention | completed | disapproved | in-review | temporarily-closed-to-accrual | temporarily-closed-to-accrual-and-intervention | withdrawn */
        #[NotBlank]
        public ?\FHIRResearchStudyStatusType $status = null,
        /** @var FHIRCodeableConcept|null primaryPurposeType treatment | prevention | diagnostic | supportive-care | screening | health-services-research | basic-science | device-feasibility */
        public ?\FHIRCodeableConcept $primaryPurposeType = null,
        /** @var FHIRCodeableConcept|null phase n-a | early-phase-1 | phase-1 | phase-1-phase-2 | phase-2 | phase-2-phase-3 | phase-3 | phase-4 */
        public ?\FHIRCodeableConcept $phase = null,
        /** @var array<FHIRCodeableConcept> category Classifications for the study */
        public array $category = [],
        /** @var array<FHIRCodeableConcept> focus Drugs, devices, etc. under study */
        public array $focus = [],
        /** @var array<FHIRCodeableConcept> condition Condition being studied */
        public array $condition = [],
        /** @var array<FHIRContactDetail> contact Contact details for the study */
        public array $contact = [],
        /** @var array<FHIRRelatedArtifact> relatedArtifact References and dependencies */
        public array $relatedArtifact = [],
        /** @var array<FHIRCodeableConcept> keyword Used to search for the study */
        public array $keyword = [],
        /** @var array<FHIRCodeableConcept> location Geographic region(s) for study */
        public array $location = [],
        /** @var FHIRMarkdown|null description What this is study doing */
        public ?\FHIRMarkdown $description = null,
        /** @var array<FHIRReference> enrollment Inclusion & exclusion criteria */
        public array $enrollment = [],
        /** @var FHIRPeriod|null period When the study began and ended */
        public ?\FHIRPeriod $period = null,
        /** @var FHIRReference|null sponsor Organization that initiates and is legally responsible for the study */
        public ?\FHIRReference $sponsor = null,
        /** @var FHIRReference|null principalInvestigator Researcher who oversees multiple aspects of the study */
        public ?\FHIRReference $principalInvestigator = null,
        /** @var array<FHIRReference> site Facility where study activities are conducted */
        public array $site = [],
        /** @var FHIRCodeableConcept|null reasonStopped accrual-goal-met | closed-due-to-toxicity | closed-due-to-lack-of-study-progress | temporarily-closed-per-study-design */
        public ?\FHIRCodeableConcept $reasonStopped = null,
        /** @var array<FHIRAnnotation> note Comments made about the study */
        public array $note = [],
        /** @var array<FHIRResearchStudyArm> arm Defined path through the study for a subject */
        public array $arm = [],
        /** @var array<FHIRResearchStudyObjective> objective A goal for the study */
        public array $objective = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
