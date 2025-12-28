<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Indicates modular components to be provided in addition or mixed with the base formula.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'NutritionOrder', elementPath: 'NutritionOrder.enteralFormula.additive', fhirVersion: 'R5')]
class FHIRNutritionOrderEnteralFormulaAdditive extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference type Type of modular component to add to the feeding */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string productName Product or brand name of the modular additive */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $productName = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity quantity Amount of additive to be given or mixed in */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity $quantity = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
