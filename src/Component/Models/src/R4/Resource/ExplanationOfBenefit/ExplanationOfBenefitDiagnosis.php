<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Information about diagnoses relevant to the claim items.
 */
#[FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.diagnosis', fhirVersion: 'R4')]
class ExplanationOfBenefitDiagnosis extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var PositiveIntPrimitive|null sequence Diagnosis instance identifier */
        #[NotBlank]
        public ?PositiveIntPrimitive $sequence = null,
        /** @var CodeableConcept|Reference|null diagnosisX Nature of illness or problem */
        #[NotBlank]
        public CodeableConcept|Reference|null $diagnosisX = null,
        /** @var array<CodeableConcept> type Timing or nature of the diagnosis */
        public array $type = [],
        /** @var CodeableConcept|null onAdmission Present on admission */
        public ?CodeableConcept $onAdmission = null,
        /** @var CodeableConcept|null packageCode Package billing code */
        public ?CodeableConcept $packageCode = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
