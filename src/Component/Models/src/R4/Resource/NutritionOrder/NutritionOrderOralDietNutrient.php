<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\NutritionOrder;

/**
 * @description Class that defines the quantity and type of nutrient modifications (for example carbohydrate, fiber or sodium) required for the oral diet.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'NutritionOrder', elementPath: 'NutritionOrder.oralDiet.nutrient', fhirVersion: 'R4')]
class NutritionOrderOralDietNutrient extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept modifier Type of nutrient that is being modified */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $modifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity amount Quantity of the specified nutrient */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $amount = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
