<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRTiming;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;

/**
 * @description Diet given orally in contrast to enteral (tube) feeding.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'NutritionOrder', elementPath: 'NutritionOrder.oralDiet', fhirVersion: 'R4')]
class FHIRNutritionOrderOralDiet extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRCodeableConcept> type Type of oral diet or diet restrictions that describe what can be consumed orally */
        public array $type = [],
        /** @var array<FHIRTiming> schedule Scheduled frequency of diet */
        public array $schedule = [],
        /** @var array<FHIRNutritionOrderOralDietNutrient> nutrient Required  nutrient modifications */
        public array $nutrient = [],
        /** @var array<FHIRNutritionOrderOralDietTexture> texture Required  texture modifications */
        public array $texture = [],
        /** @var array<FHIRCodeableConcept> fluidConsistencyType The required consistency of fluids and liquids provided to the patient */
        public array $fluidConsistencyType = [],
        /** @var FHIRString|string|null instruction Instructions or additional information about the oral diet */
        public FHIRString|string|null $instruction = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
