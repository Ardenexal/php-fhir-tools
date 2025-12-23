<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRSupplyRequestStatus
 * @description Code type wrapper for FHIRSupplyRequestStatus enum
 */
class FHIRFHIRSupplyRequestStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSupplyRequestStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSupplyRequestStatus|string|null $value = null,
	) {
	}
}
