<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;

/**
 * @description The estimated risk of the outcome.
 */
#[FHIRBackboneElement(parentResource: 'RiskEvidenceSynthesis', elementPath: 'RiskEvidenceSynthesis.riskEstimate', fhirVersion: 'R4')]
class FHIRRiskEvidenceSynthesisRiskEstimate extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null description Description of risk estimate */
        public FHIRString|string|null $description = null,
        /** @var FHIRCodeableConcept|null type Type of risk estimate */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRDecimal|null value Point estimate */
        public ?FHIRDecimal $value = null,
        /** @var FHIRCodeableConcept|null unitOfMeasure What unit is the outcome described in? */
        public ?FHIRCodeableConcept $unitOfMeasure = null,
        /** @var FHIRInteger|null denominatorCount Sample size for group measured */
        public ?FHIRInteger $denominatorCount = null,
        /** @var FHIRInteger|null numeratorCount Number with the outcome */
        public ?FHIRInteger $numeratorCount = null,
        /** @var array<FHIRRiskEvidenceSynthesisRiskEstimatePrecisionEstimate> precisionEstimate How precise the estimate is */
        public array $precisionEstimate = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
