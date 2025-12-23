<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRDeviceUsageStatus
 * @description Code type wrapper for FHIRDeviceUsageStatus enum
 */
class FHIRFHIRDeviceUsageStatusType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRDeviceUsageStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRDeviceUsageStatus|string|null $value = null,
	) {
	}
}
