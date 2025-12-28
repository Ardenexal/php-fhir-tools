<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRSupplyDeliverySupplyItemType
 * @description Code type wrapper for FHIRSupplyDeliverySupplyItemType enum
 */
class FHIRSupplyDeliverySupplyItemTypeType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRSupplyDeliverySupplyItemType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRSupplyDeliverySupplyItemType|string|null $value = null,
	) {
	}
}
