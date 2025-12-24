<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 *
 * @see http://hl7.org/fhir/StructureDefinition/RiskAssessment
 *
 * @description An assessment of the likely outcome(s) for a patient or other subject as well as the likelihood of each outcome.
 */
#[FhirResource(
    type: 'RiskAssessment',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/RiskAssessment',
    fhirVersion: 'R4',
)]
class FHIRRiskAssessment extends FHIRDomainResource
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
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Unique identifier for the assessment */
        public array $identifier = [],
        /** @var FHIRReference|null basedOn Request fulfilled by this assessment */
        public ?FHIRReference $basedOn = null,
        /** @var FHIRReference|null parent Part of this occurrence */
        public ?FHIRReference $parent = null,
        /** @var FHIRObservationStatusType|null status registered | preliminary | final | amended + */
        #[NotBlank]
        public ?FHIRObservationStatusType $status = null,
        /** @var FHIRCodeableConcept|null method Evaluation mechanism */
        public ?FHIRCodeableConcept $method = null,
        /** @var FHIRCodeableConcept|null code Type of assessment */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRReference|null subject Who/what does assessment apply to? */
        #[NotBlank]
        public ?FHIRReference $subject = null,
        /** @var FHIRReference|null encounter Where was assessment performed? */
        public ?FHIRReference $encounter = null,
        /** @var FHIRDateTime|FHIRPeriod|null occurrenceX When was assessment made? */
        public FHIRDateTime|FHIRPeriod|null $occurrenceX = null,
        /** @var FHIRReference|null condition Condition assessed */
        public ?FHIRReference $condition = null,
        /** @var FHIRReference|null performer Who did assessment? */
        public ?FHIRReference $performer = null,
        /** @var array<FHIRCodeableConcept> reasonCode Why the assessment was necessary? */
        public array $reasonCode = [],
        /** @var array<FHIRReference> reasonReference Why the assessment was necessary? */
        public array $reasonReference = [],
        /** @var array<FHIRReference> basis Information used in assessment */
        public array $basis = [],
        /** @var array<FHIRRiskAssessmentPrediction> prediction Outcome predicted */
        public array $prediction = [],
        /** @var FHIRString|string|null mitigation How to reduce risk */
        public FHIRString|string|null $mitigation = null,
        /** @var array<FHIRAnnotation> note Comments on the risk assessment */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
