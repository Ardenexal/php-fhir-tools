<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\NutritionOrder;

/**
 * @description Class that describes any texture modifications required for the patient to safely consume various types of solid foods.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'NutritionOrder', elementPath: 'NutritionOrder.oralDiet.texture', fhirVersion: 'R4')]
class NutritionOrderOralDietTexture extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept modifier Code to indicate how to alter the texture of the foods, e.g. pureed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $modifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept foodType Concepts that are used to identify an entity that is ingested for nutritional purposes */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $foodType = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
