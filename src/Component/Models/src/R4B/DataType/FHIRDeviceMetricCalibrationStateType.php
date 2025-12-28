<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRDeviceMetricCalibrationState
 * @description Code type wrapper for FHIRDeviceMetricCalibrationState enum
 */
class FHIRDeviceMetricCalibrationStateType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDeviceMetricCalibrationState|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDeviceMetricCalibrationState|string|null $value = null,
	) {
	}
}
