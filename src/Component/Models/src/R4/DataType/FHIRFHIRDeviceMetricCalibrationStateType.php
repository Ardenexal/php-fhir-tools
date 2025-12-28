<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDeviceMetricCalibrationState;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDeviceMetricCalibrationState
 *
 * @description Code type wrapper for FHIRDeviceMetricCalibrationState enum
 */
class FHIRFHIRDeviceMetricCalibrationStateType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRDeviceMetricCalibrationState|string|null $value The code value */
        public FHIRFHIRDeviceMetricCalibrationState|string|null $value = null,
    ) {
    }
}
