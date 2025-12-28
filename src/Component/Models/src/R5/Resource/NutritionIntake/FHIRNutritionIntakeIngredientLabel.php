<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Total nutrient amounts for the whole meal, product, serving, etc.
 */
#[FHIRBackboneElement(parentResource: 'NutritionIntake', elementPath: 'NutritionIntake.ingredientLabel', fhirVersion: 'R5')]
class FHIRNutritionIntakeIngredientLabel extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableReference|null nutrient Total nutrient consumed */
        #[NotBlank]
        public ?\FHIRCodeableReference $nutrient = null,
        /** @var FHIRQuantity|null amount Total amount of nutrient consumed */
        #[NotBlank]
        public ?\FHIRQuantity $amount = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
