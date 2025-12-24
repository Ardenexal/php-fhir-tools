<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;

/**
 * @description Describes the expected outcome for the subject.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'RiskAssessment', elementPath: 'RiskAssessment.prediction', fhirVersion: 'R4')]
class FHIRRiskAssessmentPrediction extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null outcome Possible outcome for the subject */
        public ?FHIRCodeableConcept $outcome = null,
        /** @var FHIRDecimal|FHIRRange|null probabilityX Likelihood of specified outcome */
        public FHIRDecimal|FHIRRange|null $probabilityX = null,
        /** @var FHIRCodeableConcept|null qualitativeRisk Likelihood of specified outcome as a qualitative value */
        public ?FHIRCodeableConcept $qualitativeRisk = null,
        /** @var FHIRDecimal|null relativeRisk Relative likelihood */
        public ?FHIRDecimal $relativeRisk = null,
        /** @var FHIRPeriod|FHIRRange|null whenX Timeframe or age range */
        public FHIRPeriod|FHIRRange|null $whenX = null,
        /** @var FHIRString|string|null rationale Explanation of prediction */
        public FHIRString|string|null $rationale = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
