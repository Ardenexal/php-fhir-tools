<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\RiskEvidenceSynthesis;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description The estimated risk of the outcome.
 */
#[FHIRBackboneElement(parentResource: 'RiskEvidenceSynthesis', elementPath: 'RiskEvidenceSynthesis.riskEstimate', fhirVersion: 'R4')]
class RiskEvidenceSynthesisRiskEstimate extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null description Description of risk estimate */
        public StringPrimitive|string|null $description = null,
        /** @var CodeableConcept|null type Type of risk estimate */
        public ?CodeableConcept $type = null,
        /** @var float|null value Point estimate */
        public ?float $value = null,
        /** @var CodeableConcept|null unitOfMeasure What unit is the outcome described in? */
        public ?CodeableConcept $unitOfMeasure = null,
        /** @var int|null denominatorCount Sample size for group measured */
        public ?int $denominatorCount = null,
        /** @var int|null numeratorCount Number with the outcome */
        public ?int $numeratorCount = null,
        /** @var array<RiskEvidenceSynthesisRiskEstimatePrecisionEstimate> precisionEstimate How precise the estimate is */
        public array $precisionEstimate = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
