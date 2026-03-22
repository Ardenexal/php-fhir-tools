<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\DeviceMetricColor;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type DeviceMetricColor
 *
 * @description Code type wrapper for DeviceMetricColor enum
 */
class DeviceMetricColorType extends CodePrimitive
{
    public function __construct(
        /** @param DeviceMetricColor|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
