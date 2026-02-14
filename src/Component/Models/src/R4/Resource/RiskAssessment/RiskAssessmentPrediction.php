<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\RiskAssessment;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Describes the expected outcome for the subject.
 */
#[FHIRBackboneElement(parentResource: 'RiskAssessment', elementPath: 'RiskAssessment.prediction', fhirVersion: 'R4')]
class RiskAssessmentPrediction extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null outcome Possible outcome for the subject */
        public ?CodeableConcept $outcome = null,
        /** @var float|Range|null probabilityX Likelihood of specified outcome */
        public float|Range|null $probabilityX = null,
        /** @var CodeableConcept|null qualitativeRisk Likelihood of specified outcome as a qualitative value */
        public ?CodeableConcept $qualitativeRisk = null,
        /** @var float|null relativeRisk Relative likelihood */
        public ?float $relativeRisk = null,
        /** @var Period|Range|null whenX Timeframe or age range */
        public Period|Range|null $whenX = null,
        /** @var StringPrimitive|string|null rationale Explanation of prediction */
        public StringPrimitive|string|null $rationale = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
