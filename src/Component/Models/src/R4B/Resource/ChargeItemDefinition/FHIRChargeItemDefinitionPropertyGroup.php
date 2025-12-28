<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description Group of properties which are applicable under the same conditions. If no applicability rules are established for the group, then all properties always apply.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ChargeItemDefinition', elementPath: 'ChargeItemDefinition.propertyGroup', fhirVersion: 'R4B')]
class FHIRChargeItemDefinitionPropertyGroup extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRChargeItemDefinitionApplicability> applicability Conditions under which the priceComponent is applicable */
		public array $applicability = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRChargeItemDefinitionPropertyGroupPriceComponent> priceComponent Components of total line item price */
		public array $priceComponent = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
