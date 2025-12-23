<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element NutritionOrder.supplement
 * @description Oral nutritional products given in order to add further nutritional value to the patient's diet.
 */
class FHIRNutritionOrderSupplement extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference type Type of supplement product requested */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string productName Product or brand name of the nutritional supplement */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $productName = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNutritionOrderSupplementSchedule schedule Scheduling information for supplements */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNutritionOrderSupplementSchedule $schedule = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity quantity Amount of the nutritional supplement */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string instruction Instructions or additional information about the oral supplement */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $instruction = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
