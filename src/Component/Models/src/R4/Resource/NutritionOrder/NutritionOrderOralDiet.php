<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\NutritionOrder;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Diet given orally in contrast to enteral (tube) feeding.
 */
#[FHIRBackboneElement(parentResource: 'NutritionOrder', elementPath: 'NutritionOrder.oralDiet', fhirVersion: 'R4')]
class NutritionOrderOralDiet extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<CodeableConcept> type Type of oral diet or diet restrictions that describe what can be consumed orally */
        public array $type = [],
        /** @var array<Timing> schedule Scheduled frequency of diet */
        public array $schedule = [],
        /** @var array<NutritionOrderOralDietNutrient> nutrient Required  nutrient modifications */
        public array $nutrient = [],
        /** @var array<NutritionOrderOralDietTexture> texture Required  texture modifications */
        public array $texture = [],
        /** @var array<CodeableConcept> fluidConsistencyType The required consistency of fluids and liquids provided to the patient */
        public array $fluidConsistencyType = [],
        /** @var StringPrimitive|string|null instruction Instructions or additional information about the oral diet */
        public StringPrimitive|string|null $instruction = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
