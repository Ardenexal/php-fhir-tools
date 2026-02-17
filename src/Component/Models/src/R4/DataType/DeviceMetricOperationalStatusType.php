<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\DeviceMetricOperationalStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type DeviceMetricOperationalStatus
 *
 * @description Code type wrapper for DeviceMetricOperationalStatus enum
 */
class DeviceMetricOperationalStatusType extends CodePrimitive
{
    public function __construct(
        /** @param DeviceMetricOperationalStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
