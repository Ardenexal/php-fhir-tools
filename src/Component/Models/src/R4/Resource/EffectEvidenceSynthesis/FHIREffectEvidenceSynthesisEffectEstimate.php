<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description The estimated effect of the exposure variant.
 */
#[FHIRBackboneElement(parentResource: 'EffectEvidenceSynthesis', elementPath: 'EffectEvidenceSynthesis.effectEstimate', fhirVersion: 'R4')]
class FHIREffectEvidenceSynthesisEffectEstimate extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null description Description of effect estimate */
        public \FHIRString|string|null $description = null,
        /** @var FHIRCodeableConcept|null type Type of efffect estimate */
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|null variantState Variant exposure states */
        public ?\FHIRCodeableConcept $variantState = null,
        /** @var FHIRDecimal|null value Point estimate */
        public ?\FHIRDecimal $value = null,
        /** @var FHIRCodeableConcept|null unitOfMeasure What unit is the outcome described in? */
        public ?\FHIRCodeableConcept $unitOfMeasure = null,
        /** @var array<FHIREffectEvidenceSynthesisEffectEstimatePrecisionEstimate> precisionEstimate How precise the estimate is */
        public array $precisionEstimate = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
