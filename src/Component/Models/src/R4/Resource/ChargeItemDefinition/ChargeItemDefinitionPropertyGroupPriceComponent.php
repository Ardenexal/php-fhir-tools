<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ChargeItemDefinition;

/**
 * @description The price for a ChargeItem may be calculated as a base price with surcharges/deductions that apply in certain conditions. A ChargeItemDefinition resource that defines the prices, factors and conditions that apply to a billing code is currently under development. The priceComponent element can be used to offer transparency to the recipient of the Invoice of how the prices have been calculated.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'ChargeItemDefinition',
	elementPath: 'ChargeItemDefinition.propertyGroup.priceComponent',
	fhirVersion: 'R4',
)]
class ChargeItemDefinitionPropertyGroupPriceComponent extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\InvoicePriceComponentTypeType type base | surcharge | deduction | discount | tax | informational */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\InvoicePriceComponentTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept code Code identifying the specific component */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $code = null,
		/** @var null|float factor Factor used for calculating this component */
		public ?float $factor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money amount Monetary amount associated with this component */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money $amount = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
