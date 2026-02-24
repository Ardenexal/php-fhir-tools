<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\DeviceMetricCategory;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type DeviceMetricCategory
 *
 * @description Code type wrapper for DeviceMetricCategory enum
 */
class DeviceMetricCategoryType extends CodePrimitive
{
    public function __construct(
        /** @param DeviceMetricCategory|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
