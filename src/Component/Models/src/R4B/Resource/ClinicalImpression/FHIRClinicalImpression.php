<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRClinicalImpressionStatusType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
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
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/ClinicalImpression',
    fhirVersion: 'R4B',
)]
class FHIRClinicalImpression extends FHIRDomainResource
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
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Business identifier */
        public array $identifier = [],
        /** @var FHIRClinicalImpressionStatusType|null status in-progress | completed | entered-in-error */
        #[NotBlank]
        public ?FHIRClinicalImpressionStatusType $status = null,
        /** @var FHIRCodeableConcept|null statusReason Reason for current status */
        public ?FHIRCodeableConcept $statusReason = null,
        /** @var FHIRCodeableConcept|null code Kind of assessment performed */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRString|string|null description Why/how the assessment was performed */
        public FHIRString|string|null $description = null,
        /** @var FHIRReference|null subject Patient or group assessed */
        #[NotBlank]
        public ?FHIRReference $subject = null,
        /** @var FHIRReference|null encounter Encounter created as part of */
        public ?FHIRReference $encounter = null,
        /** @var FHIRDateTime|FHIRPeriod|null effectiveX Time of assessment */
        public FHIRDateTime|FHIRPeriod|null $effectiveX = null,
        /** @var FHIRDateTime|null date When the assessment was documented */
        public ?FHIRDateTime $date = null,
        /** @var FHIRReference|null assessor The clinician performing the assessment */
        public ?FHIRReference $assessor = null,
        /** @var FHIRReference|null previous Reference to last assessment */
        public ?FHIRReference $previous = null,
        /** @var array<FHIRReference> problem Relevant impressions of patient state */
        public array $problem = [],
        /** @var array<FHIRClinicalImpressionInvestigation> investigation One or more sets of investigations (signs, symptoms, etc.) */
        public array $investigation = [],
        /** @var array<FHIRUri> protocol Clinical Protocol followed */
        public array $protocol = [],
        /** @var FHIRString|string|null summary Summary of the assessment */
        public FHIRString|string|null $summary = null,
        /** @var array<FHIRClinicalImpressionFinding> finding Possible or likely findings and diagnoses */
        public array $finding = [],
        /** @var array<FHIRCodeableConcept> prognosisCodeableConcept Estimate of likely outcome */
        public array $prognosisCodeableConcept = [],
        /** @var array<FHIRReference> prognosisReference RiskAssessment expressing likely outcome */
        public array $prognosisReference = [],
        /** @var array<FHIRReference> supportingInfo Information supporting the clinical impression */
        public array $supportingInfo = [],
        /** @var array<FHIRAnnotation> note Comments made about the ClinicalImpression */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
