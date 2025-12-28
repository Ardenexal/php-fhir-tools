<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRDeviceMetricOperationalStatus
 * @description Code type wrapper for FHIRDeviceMetricOperationalStatus enum
 */
class FHIRDeviceMetricOperationalStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDeviceMetricOperationalStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDeviceMetricOperationalStatus|string|null $value = null,
	) {
	}
}
