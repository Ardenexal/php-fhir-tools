<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Indicates modular components to be provided in addition or mixed with the base formula.
 */
#[FHIRBackboneElement(parentResource: 'NutritionOrder', elementPath: 'NutritionOrder.enteralFormula.additive', fhirVersion: 'R5')]
class FHIRNutritionOrderEnteralFormulaAdditive extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableReference|null type Type of modular component to add to the feeding */
        public ?\FHIRCodeableReference $type = null,
        /** @var FHIRString|string|null productName Product or brand name of the modular additive */
        public \FHIRString|string|null $productName = null,
        /** @var FHIRQuantity|null quantity Amount of additive to be given or mixed in */
        public ?\FHIRQuantity $quantity = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
