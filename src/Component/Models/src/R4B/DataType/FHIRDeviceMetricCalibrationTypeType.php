<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDeviceMetricCalibrationType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDeviceMetricCalibrationType
 *
 * @description Code type wrapper for FHIRDeviceMetricCalibrationType enum
 */
class FHIRDeviceMetricCalibrationTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRDeviceMetricCalibrationType|string|null $value The code value */
        public FHIRDeviceMetricCalibrationType|string|null $value = null,
    ) {
    }
}
