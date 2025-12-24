<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;

/**
 * @description Class that describes any texture modifications required for the patient to safely consume various types of solid foods.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'NutritionOrder', elementPath: 'NutritionOrder.oralDiet.texture', fhirVersion: 'R5')]
class FHIRNutritionOrderOralDietTexture extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null modifier Code to indicate how to alter the texture of the foods, e.g. pureed */
        public ?FHIRCodeableConcept $modifier = null,
        /** @var FHIRCodeableConcept|null foodType Concepts that are used to identify an entity that is ingested for nutritional purposes */
        public ?FHIRCodeableConcept $foodType = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
