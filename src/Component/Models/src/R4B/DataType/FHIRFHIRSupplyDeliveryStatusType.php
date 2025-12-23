<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRSupplyDeliveryStatus
 * @description Code type wrapper for FHIRSupplyDeliveryStatus enum
 */
class FHIRFHIRSupplyDeliveryStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSupplyDeliveryStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSupplyDeliveryStatus|string|null $value = null,
	) {
	}
}
