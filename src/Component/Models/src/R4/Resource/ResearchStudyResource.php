<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ResearchStudyStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ResearchStudy\ResearchStudyArm;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ResearchStudy\ResearchStudyObjective;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ResearchStudy
 *
 * @description A process where a researcher or organization plans and then executes a series of steps intended to increase the field of healthcare-related knowledge.  This includes studies of safety, efficacy, comparative effectiveness and other information about medications, devices, therapies and other interventional and investigative techniques.  A ResearchStudy involves the gathering of information about human or animal subjects.
 */
#[FhirResource(type: 'ResearchStudy', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/ResearchStudy', fhirVersion: 'R4')]
class ResearchStudyResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Business Identifier for study */
        public array $identifier = [],
        /** @var StringPrimitive|string|null title Name for this study */
        public StringPrimitive|string|null $title = null,
        /** @var array<Reference> protocol Steps followed in executing study */
        public array $protocol = [],
        /** @var array<Reference> partOf Part of larger study */
        public array $partOf = [],
        /** @var ResearchStudyStatusType|null status active | administratively-completed | approved | closed-to-accrual | closed-to-accrual-and-intervention | completed | disapproved | in-review | temporarily-closed-to-accrual | temporarily-closed-to-accrual-and-intervention | withdrawn */
        #[NotBlank]
        public ?ResearchStudyStatusType $status = null,
        /** @var CodeableConcept|null primaryPurposeType treatment | prevention | diagnostic | supportive-care | screening | health-services-research | basic-science | device-feasibility */
        public ?CodeableConcept $primaryPurposeType = null,
        /** @var CodeableConcept|null phase n-a | early-phase-1 | phase-1 | phase-1-phase-2 | phase-2 | phase-2-phase-3 | phase-3 | phase-4 */
        public ?CodeableConcept $phase = null,
        /** @var array<CodeableConcept> category Classifications for the study */
        public array $category = [],
        /** @var array<CodeableConcept> focus Drugs, devices, etc. under study */
        public array $focus = [],
        /** @var array<CodeableConcept> condition Condition being studied */
        public array $condition = [],
        /** @var array<ContactDetail> contact Contact details for the study */
        public array $contact = [],
        /** @var array<RelatedArtifact> relatedArtifact References and dependencies */
        public array $relatedArtifact = [],
        /** @var array<CodeableConcept> keyword Used to search for the study */
        public array $keyword = [],
        /** @var array<CodeableConcept> location Geographic region(s) for study */
        public array $location = [],
        /** @var MarkdownPrimitive|null description What this is study doing */
        public ?MarkdownPrimitive $description = null,
        /** @var array<Reference> enrollment Inclusion & exclusion criteria */
        public array $enrollment = [],
        /** @var Period|null period When the study began and ended */
        public ?Period $period = null,
        /** @var Reference|null sponsor Organization that initiates and is legally responsible for the study */
        public ?Reference $sponsor = null,
        /** @var Reference|null principalInvestigator Researcher who oversees multiple aspects of the study */
        public ?Reference $principalInvestigator = null,
        /** @var array<Reference> site Facility where study activities are conducted */
        public array $site = [],
        /** @var CodeableConcept|null reasonStopped accrual-goal-met | closed-due-to-toxicity | closed-due-to-lack-of-study-progress | temporarily-closed-per-study-design */
        public ?CodeableConcept $reasonStopped = null,
        /** @var array<Annotation> note Comments made about the study */
        public array $note = [],
        /** @var array<ResearchStudyArm> arm Defined path through the study for a subject */
        public array $arm = [],
        /** @var array<ResearchStudyObjective> objective A goal for the study */
        public array $objective = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
