<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\RelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ResearchStudy\ResearchStudyAssociatedParty;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ResearchStudy\ResearchStudyComparisonGroup;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ResearchStudy\ResearchStudyLabel;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ResearchStudy\ResearchStudyObjective;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ResearchStudy\ResearchStudyOutcomeMeasure;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ResearchStudy\ResearchStudyProgressStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ResearchStudy\ResearchStudyRecruitment;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ResearchStudy
 *
 * @description A scientific study of nature that sometimes includes processes involved in health and disease. For example, clinical trials are research studies that involve people. These studies may be related to new ways to screen, prevent, diagnose, and treat disease. They may also study certain outcomes and certain groups of people by looking at data collected in the past or future.
 */
#[FhirResource(type: 'ResearchStudy', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/ResearchStudy', fhirVersion: 'R5')]
class ResearchStudyResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $implicitRules = null,
        /** @var AllLanguagesType|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AllLanguagesType $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var UriPrimitive|null url Canonical identifier for this study resource */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $url = null,
        /** @var array<Identifier> identifier Business Identifier for study */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var StringPrimitive|string|null version The business version for the study record */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $version = null,
        /** @var StringPrimitive|string|null name Name for this study (computer friendly) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null title Human readable name of the study */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $title = null,
        /** @var array<ResearchStudyLabel> label Additional names for the study */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ResearchStudy\ResearchStudyLabel',
        )]
        public array $label = [],
        /** @var array<Reference> protocol Steps followed in executing study */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $protocol = [],
        /** @var array<Reference> partOf Part of larger study */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $partOf = [],
        /** @var array<RelatedArtifact> relatedArtifact References, URLs, and attachments */
        #[FhirProperty(
            fhirType: 'RelatedArtifact',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\RelatedArtifact',
        )]
        public array $relatedArtifact = [],
        /** @var DateTimePrimitive|null date Date the resource last changed */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $date = null,
        /** @var PublicationStatusType|null status draft | active | retired | unknown */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?PublicationStatusType $status = null,
        /** @var CodeableConcept|null primaryPurposeType treatment | prevention | diagnostic | supportive-care | screening | health-services-research | basic-science | device-feasibility */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $primaryPurposeType = null,
        /** @var CodeableConcept|null phase n-a | early-phase-1 | phase-1 | phase-1-phase-2 | phase-2 | phase-2-phase-3 | phase-3 | phase-4 */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $phase = null,
        /** @var array<CodeableConcept> studyDesign Classifications of the study design characteristics */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $studyDesign = [],
        /** @var array<CodeableReference> focus Drugs, devices, etc. under study */
        #[FhirProperty(
            fhirType: 'CodeableReference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference',
        )]
        public array $focus = [],
        /** @var array<CodeableConcept> condition Condition being studied */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $condition = [],
        /** @var array<CodeableConcept> keyword Used to search for the study */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $keyword = [],
        /** @var array<CodeableConcept> region Geographic area for the study */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $region = [],
        /** @var MarkdownPrimitive|null descriptionSummary Brief text explaining the study */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $descriptionSummary = null,
        /** @var MarkdownPrimitive|null description Detailed narrative of the study */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $description = null,
        /** @var Period|null period When the study began and ended */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?Period $period = null,
        /** @var array<Reference> site Facility where study activities are conducted */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $site = [],
        /** @var array<Annotation> note Comments made about the study */
        #[FhirProperty(
            fhirType: 'Annotation',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Annotation',
        )]
        public array $note = [],
        /** @var array<CodeableConcept> classifier Classification for the study */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $classifier = [],
        /** @var array<ResearchStudyAssociatedParty> associatedParty Sponsors, collaborators, and other parties */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ResearchStudy\ResearchStudyAssociatedParty',
        )]
        public array $associatedParty = [],
        /** @var array<ResearchStudyProgressStatus> progressStatus Status of study with time for that status */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ResearchStudy\ResearchStudyProgressStatus',
        )]
        public array $progressStatus = [],
        /** @var CodeableConcept|null whyStopped accrual-goal-met | closed-due-to-toxicity | closed-due-to-lack-of-study-progress | temporarily-closed-per-study-design */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $whyStopped = null,
        /** @var ResearchStudyRecruitment|null recruitment Target or actual group of participants enrolled in study */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?ResearchStudyRecruitment $recruitment = null,
        /** @var array<ResearchStudyComparisonGroup> comparisonGroup Defined path through the study for a subject */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ResearchStudy\ResearchStudyComparisonGroup',
        )]
        public array $comparisonGroup = [],
        /** @var array<ResearchStudyObjective> objective A goal for the study */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ResearchStudy\ResearchStudyObjective',
        )]
        public array $objective = [],
        /** @var array<ResearchStudyOutcomeMeasure> outcomeMeasure A variable measured during the study */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ResearchStudy\ResearchStudyOutcomeMeasure',
        )]
        public array $outcomeMeasure = [],
        /** @var array<Reference> result Link to results generated during the study */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $result = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
