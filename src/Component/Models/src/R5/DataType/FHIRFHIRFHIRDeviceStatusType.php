<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRFHIRDeviceStatus
 * @description Code type wrapper for FHIRFHIRDeviceStatus enum
 */
class FHIRFHIRFHIRDeviceStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRFHIRDeviceStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRFHIRDeviceStatus|string|null $value = null,
	) {
	}
}
