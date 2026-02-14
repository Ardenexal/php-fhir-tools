<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\NutritionOrder;

/**
 * @description Diet given orally in contrast to enteral (tube) feeding.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'NutritionOrder', elementPath: 'NutritionOrder.oralDiet', fhirVersion: 'R4')]
class NutritionOrderOralDiet extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> type Type of oral diet or diet restrictions that describe what can be consumed orally */
		public array $type = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing> schedule Scheduled frequency of diet */
		public array $schedule = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\NutritionOrder\NutritionOrderOralDietNutrient> nutrient Required  nutrient modifications */
		public array $nutrient = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\NutritionOrder\NutritionOrderOralDietTexture> texture Required  texture modifications */
		public array $texture = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> fluidConsistencyType The required consistency of fluids and liquids provided to the patient */
		public array $fluidConsistencyType = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string instruction Instructions or additional information about the oral diet */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $instruction = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
