<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element NutritionOrder.oralDiet
 * @description Diet given orally in contrast to enteral (tube) feeding.
 */
class FHIRNutritionOrderOralDiet extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> type Type of oral diet or diet restrictions that describe what can be consumed orally */
		public array $type = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNutritionOrderOralDietSchedule schedule Scheduling information for oral diets */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNutritionOrderOralDietSchedule $schedule = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNutritionOrderOralDietNutrient> nutrient Required  nutrient modifications */
		public array $nutrient = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNutritionOrderOralDietTexture> texture Required  texture modifications */
		public array $texture = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> fluidConsistencyType The required consistency of fluids and liquids provided to the patient */
		public array $fluidConsistencyType = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string instruction Instructions or additional information about the oral diet */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $instruction = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
