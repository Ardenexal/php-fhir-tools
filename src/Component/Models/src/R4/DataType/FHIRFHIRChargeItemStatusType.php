<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRChargeItemStatus
 * @description Code type wrapper for FHIRChargeItemStatus enum
 */
class FHIRFHIRChargeItemStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRChargeItemStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRChargeItemStatus|string|null $value = null,
	) {
	}
}
