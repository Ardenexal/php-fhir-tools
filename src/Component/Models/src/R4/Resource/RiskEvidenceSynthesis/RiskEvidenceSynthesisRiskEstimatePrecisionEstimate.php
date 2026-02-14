<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\RiskEvidenceSynthesis;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @description A description of the precision of the estimate for the effect.
 */
#[FHIRBackboneElement(
    parentResource: 'RiskEvidenceSynthesis',
    elementPath: 'RiskEvidenceSynthesis.riskEstimate.precisionEstimate',
    fhirVersion: 'R4',
)]
class RiskEvidenceSynthesisRiskEstimatePrecisionEstimate extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null type Type of precision estimate */
        public ?CodeableConcept $type = null,
        /** @var float|null level Level of confidence interval */
        public ?float $level = null,
        /** @var float|null from Lower bound */
        public ?float $from = null,
        /** @var float|null to Upper bound */
        public ?float $to = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
