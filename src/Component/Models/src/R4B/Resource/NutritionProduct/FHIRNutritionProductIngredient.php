<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Ingredients contained in this product.
 */
#[FHIRBackboneElement(parentResource: 'NutritionProduct', elementPath: 'NutritionProduct.ingredient', fhirVersion: 'R4B')]
class FHIRNutritionProductIngredient extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableReference|null item The ingredient contained in the product */
        #[NotBlank]
        public ?\FHIRCodeableReference $item = null,
        /** @var array<FHIRRatio> amount The amount of ingredient that is in the product */
        public array $amount = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
