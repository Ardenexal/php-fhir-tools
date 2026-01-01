<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description The price for a ChargeItem may be calculated as a base price with surcharges/deductions that apply in certain conditions. A ChargeItemDefinition resource that defines the prices, factors and conditions that apply to a billing code is currently under development. The priceComponent element can be used to offer transparency to the recipient of the Invoice of how the prices have been calculated.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'ChargeItemDefinition',
	elementPath: 'ChargeItemDefinition.propertyGroup.priceComponent',
	fhirVersion: 'R4',
)]
class FHIRChargeItemDefinitionPropertyGroupPriceComponent extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRInvoicePriceComponentTypeType type base | surcharge | deduction | discount | tax | informational */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRInvoicePriceComponentTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept code Code identifying the specific component */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal factor Factor used for calculating this component */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal $factor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMoney amount Monetary amount associated with this component */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMoney $amount = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
