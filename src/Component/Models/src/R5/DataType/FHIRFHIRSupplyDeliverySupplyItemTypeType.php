<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRSupplyDeliverySupplyItemType
 * @description Code type wrapper for FHIRSupplyDeliverySupplyItemType enum
 */
class FHIRFHIRSupplyDeliverySupplyItemTypeType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRSupplyDeliverySupplyItemType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRSupplyDeliverySupplyItemType|string|null $value = null,
	) {
	}
}
