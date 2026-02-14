<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ClinicalImpressionStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ClinicalImpression\ClinicalImpressionFinding;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ClinicalImpression\ClinicalImpressionInvestigation;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ClinicalImpression
 *
 * @description A record of a clinical assessment performed to determine what problem(s) may affect the patient and before planning the treatments or management strategies that are best to manage a patient's condition. Assessments are often 1:1 with a clinical consultation / encounter,  but this varies greatly depending on the clinical workflow. This resource is called "ClinicalImpression" rather than "ClinicalAssessment" to avoid confusion with the recording of assessment tools such as Apgar score.
 */
#[FhirResource(
    type: 'ClinicalImpression',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/ClinicalImpression',
    fhirVersion: 'R4',
)]
class ClinicalImpressionResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Business identifier */
        public array $identifier = [],
        /** @var ClinicalImpressionStatusType|null status in-progress | completed | entered-in-error */
        #[NotBlank]
        public ?ClinicalImpressionStatusType $status = null,
        /** @var CodeableConcept|null statusReason Reason for current status */
        public ?CodeableConcept $statusReason = null,
        /** @var CodeableConcept|null code Kind of assessment performed */
        public ?CodeableConcept $code = null,
        /** @var StringPrimitive|string|null description Why/how the assessment was performed */
        public StringPrimitive|string|null $description = null,
        /** @var Reference|null subject Patient or group assessed */
        #[NotBlank]
        public ?Reference $subject = null,
        /** @var Reference|null encounter Encounter created as part of */
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|Period|null effectiveX Time of assessment */
        public DateTimePrimitive|Period|null $effectiveX = null,
        /** @var DateTimePrimitive|null date When the assessment was documented */
        public ?DateTimePrimitive $date = null,
        /** @var Reference|null assessor The clinician performing the assessment */
        public ?Reference $assessor = null,
        /** @var Reference|null previous Reference to last assessment */
        public ?Reference $previous = null,
        /** @var array<Reference> problem Relevant impressions of patient state */
        public array $problem = [],
        /** @var array<ClinicalImpressionInvestigation> investigation One or more sets of investigations (signs, symptoms, etc.) */
        public array $investigation = [],
        /** @var array<UriPrimitive> protocol Clinical Protocol followed */
        public array $protocol = [],
        /** @var StringPrimitive|string|null summary Summary of the assessment */
        public StringPrimitive|string|null $summary = null,
        /** @var array<ClinicalImpressionFinding> finding Possible or likely findings and diagnoses */
        public array $finding = [],
        /** @var array<CodeableConcept> prognosisCodeableConcept Estimate of likely outcome */
        public array $prognosisCodeableConcept = [],
        /** @var array<Reference> prognosisReference RiskAssessment expressing likely outcome */
        public array $prognosisReference = [],
        /** @var array<Reference> supportingInfo Information supporting the clinical impression */
        public array $supportingInfo = [],
        /** @var array<Annotation> note Comments made about the ClinicalImpression */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
