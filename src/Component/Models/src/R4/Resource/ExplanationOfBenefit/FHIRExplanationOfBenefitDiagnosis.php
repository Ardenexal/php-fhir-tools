<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Information about diagnoses relevant to the claim items.
 */
#[FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.diagnosis', fhirVersion: 'R4')]
class FHIRExplanationOfBenefitDiagnosis extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRPositiveInt|null sequence Diagnosis instance identifier */
        #[NotBlank]
        public ?\FHIRPositiveInt $sequence = null,
        /** @var FHIRCodeableConcept|FHIRReference|null diagnosisX Nature of illness or problem */
        #[NotBlank]
        public \FHIRCodeableConcept|\FHIRReference|null $diagnosisX = null,
        /** @var array<FHIRCodeableConcept> type Timing or nature of the diagnosis */
        public array $type = [],
        /** @var FHIRCodeableConcept|null onAdmission Present on admission */
        public ?\FHIRCodeableConcept $onAdmission = null,
        /** @var FHIRCodeableConcept|null packageCode Package billing code */
        public ?\FHIRCodeableConcept $packageCode = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
