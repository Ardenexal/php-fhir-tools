<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRDeviceMetricCalibrationType
 * @description Code type wrapper for FHIRDeviceMetricCalibrationType enum
 */
class FHIRDeviceMetricCalibrationTypeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDeviceMetricCalibrationType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDeviceMetricCalibrationType|string|null $value = null,
	) {
	}
}
