<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\NutritionOrder;

/**
 * @description Oral nutritional products given in order to add further nutritional value to the patient's diet.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'NutritionOrder', elementPath: 'NutritionOrder.supplement', fhirVersion: 'R4')]
class NutritionOrderSupplement extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type Type of supplement product requested */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string productName Product or brand name of the nutritional supplement */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $productName = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing> schedule Scheduled frequency of supplement */
		public array $schedule = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity quantity Amount of the nutritional supplement */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string instruction Instructions or additional information about the oral supplement */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $instruction = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
