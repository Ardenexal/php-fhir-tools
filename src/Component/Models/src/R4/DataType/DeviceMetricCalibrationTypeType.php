<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\DeviceMetricCalibrationType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type DeviceMetricCalibrationType
 *
 * @description Code type wrapper for DeviceMetricCalibrationType enum
 */
class DeviceMetricCalibrationTypeType extends CodePrimitive
{
    public function __construct(
        /** @param DeviceMetricCalibrationType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
