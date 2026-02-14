<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ObservationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\RiskAssessment\RiskAssessmentPrediction;
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
class RiskAssessmentResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Unique identifier for the assessment */
        public array $identifier = [],
        /** @var Reference|null basedOn Request fulfilled by this assessment */
        public ?Reference $basedOn = null,
        /** @var Reference|null parent Part of this occurrence */
        public ?Reference $parent = null,
        /** @var ObservationStatusType|null status registered | preliminary | final | amended + */
        #[NotBlank]
        public ?ObservationStatusType $status = null,
        /** @var CodeableConcept|null method Evaluation mechanism */
        public ?CodeableConcept $method = null,
        /** @var CodeableConcept|null code Type of assessment */
        public ?CodeableConcept $code = null,
        /** @var Reference|null subject Who/what does assessment apply to? */
        #[NotBlank]
        public ?Reference $subject = null,
        /** @var Reference|null encounter Where was assessment performed? */
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|Period|null occurrenceX When was assessment made? */
        public DateTimePrimitive|Period|null $occurrenceX = null,
        /** @var Reference|null condition Condition assessed */
        public ?Reference $condition = null,
        /** @var Reference|null performer Who did assessment? */
        public ?Reference $performer = null,
        /** @var array<CodeableConcept> reasonCode Why the assessment was necessary? */
        public array $reasonCode = [],
        /** @var array<Reference> reasonReference Why the assessment was necessary? */
        public array $reasonReference = [],
        /** @var array<Reference> basis Information used in assessment */
        public array $basis = [],
        /** @var array<RiskAssessmentPrediction> prediction Outcome predicted */
        public array $prediction = [],
        /** @var StringPrimitive|string|null mitigation How to reduce risk */
        public StringPrimitive|string|null $mitigation = null,
        /** @var array<Annotation> note Comments on the risk assessment */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
