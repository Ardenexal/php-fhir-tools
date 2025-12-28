<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description A description of the precision of the estimate for the effect.
 */
#[FHIRBackboneElement(
    parentResource: 'EffectEvidenceSynthesis',
    elementPath: 'EffectEvidenceSynthesis.effectEstimate.precisionEstimate',
    fhirVersion: 'R5',
)]
class FHIREffectEvidenceSynthesisEffectEstimatePrecisionEstimate extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Type of precision estimate */
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRDecimal|null level Level of confidence interval */
        public ?\FHIRDecimal $level = null,
        /** @var FHIRDecimal|null from Lower bound */
        public ?\FHIRDecimal $from = null,
        /** @var FHIRDecimal|null to Upper bound */
        public ?\FHIRDecimal $to = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
