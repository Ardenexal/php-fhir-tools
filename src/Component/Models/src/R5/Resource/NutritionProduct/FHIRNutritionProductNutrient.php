<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description The product's nutritional information expressed by the nutrients.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'NutritionProduct', elementPath: 'NutritionProduct.nutrient', fhirVersion: 'R5')]
class FHIRNutritionProductNutrient extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference item The (relevant) nutrients in the product */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference $item = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRatio> amount The amount of nutrient expressed in one or more units: X per pack / per serving / per dose */
		public array $amount = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
