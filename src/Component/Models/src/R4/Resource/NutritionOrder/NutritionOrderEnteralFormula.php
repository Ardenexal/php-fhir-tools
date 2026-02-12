<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\NutritionOrder;

/**
 * @description Feeding provided through the gastrointestinal tract via a tube, catheter, or stoma that delivers nutrition distal to the oral cavity.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'NutritionOrder', elementPath: 'NutritionOrder.enteralFormula', fhirVersion: 'R4')]
class NutritionOrderEnteralFormula extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept baseFormulaType Type of enteral or infant formula */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $baseFormulaType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string baseFormulaProductName Product or brand name of the enteral or infant formula */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $baseFormulaProductName = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept additiveType Type of modular component to add to the feeding */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $additiveType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string additiveProductName Product or brand name of the modular additive */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $additiveProductName = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity caloricDensity Amount of energy per specified volume that is required */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $caloricDensity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept routeofAdministration How the formula should enter the patient's gastrointestinal tract */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $routeofAdministration = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\NutritionOrder\NutritionOrderEnteralFormulaAdministration> administration Formula feeding instruction as structured data */
		public array $administration = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity maxVolumeToDeliver Upper limit on formula volume per unit of time */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $maxVolumeToDeliver = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string administrationInstruction Formula feeding instructions expressed as text */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $administrationInstruction = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
