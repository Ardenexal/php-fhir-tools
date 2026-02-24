<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\DeviceMetricCalibrationType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

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
