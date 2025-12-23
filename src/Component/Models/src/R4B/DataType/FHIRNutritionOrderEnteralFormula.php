<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element NutritionOrder.enteralFormula
 * @description Feeding provided through the gastrointestinal tract via a tube, catheter, or stoma that delivers nutrition distal to the oral cavity.
 */
class FHIRNutritionOrderEnteralFormula extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept baseFormulaType Type of enteral or infant formula */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $baseFormulaType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string baseFormulaProductName Product or brand name of the enteral or infant formula */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $baseFormulaProductName = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept additiveType Type of modular component to add to the feeding */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $additiveType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string additiveProductName Product or brand name of the modular additive */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $additiveProductName = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity caloricDensity Amount of energy per specified volume that is required */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity $caloricDensity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept routeofAdministration How the formula should enter the patient's gastrointestinal tract */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $routeofAdministration = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRNutritionOrderEnteralFormulaAdministration> administration Formula feeding instruction as structured data */
		public array $administration = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity maxVolumeToDeliver Upper limit on formula volume per unit of time */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity $maxVolumeToDeliver = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string administrationInstruction Formula feeding instructions expressed as text */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $administrationInstruction = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
