<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDeviceMetricCalibrationType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRDeviceMetricCalibrationType
 *
 * @description Code type wrapper for FHIRDeviceMetricCalibrationType enum
 */
class FHIRFHIRDeviceMetricCalibrationTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRDeviceMetricCalibrationType|string|null $value The code value */
        public FHIRFHIRDeviceMetricCalibrationType|string|null $value = null,
    ) {
    }
}
