<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description The product's nutritional information expressed by the nutrients.
 */
#[FHIRBackboneElement(parentResource: 'NutritionProduct', elementPath: 'NutritionProduct.nutrient', fhirVersion: 'R4B')]
class FHIRNutritionProductNutrient extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableReference|null item The (relevant) nutrients in the product */
        public ?\FHIRCodeableReference $item = null,
        /** @var array<FHIRRatio> amount The amount of nutrient expressed in one or more units: X per pack / per serving / per dose */
        public array $amount = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
