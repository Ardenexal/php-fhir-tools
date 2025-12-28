<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Feeding provided through the gastrointestinal tract via a tube, catheter, or stoma that delivers nutrition distal to the oral cavity.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'NutritionOrder', elementPath: 'NutritionOrder.enteralFormula', fhirVersion: 'R5')]
class FHIRNutritionOrderEnteralFormula extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference baseFormulaType Type of enteral or infant formula */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference $baseFormulaType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string baseFormulaProductName Product or brand name of the enteral or infant formula */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $baseFormulaProductName = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference> deliveryDevice Intended type of device for the administration */
		public array $deliveryDevice = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNutritionOrderEnteralFormulaAdditive> additive Components to add to the feeding */
		public array $additive = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity caloricDensity Amount of energy per specified volume that is required */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity $caloricDensity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept routeOfAdministration How the formula should enter the patient's gastrointestinal tract */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $routeOfAdministration = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNutritionOrderEnteralFormulaAdministration> administration Formula feeding instruction as structured data */
		public array $administration = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity maxVolumeToDeliver Upper limit on formula volume per unit of time */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity $maxVolumeToDeliver = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown administrationInstruction Formula feeding instructions expressed as text */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $administrationInstruction = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
