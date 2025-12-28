<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDeviceMetricCategory;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDeviceMetricCategory
 *
 * @description Code type wrapper for FHIRDeviceMetricCategory enum
 */
class FHIRDeviceMetricCategoryType extends FHIRCode
{
    public function __construct(
        /** @var FHIRDeviceMetricCategory|string|null $value The code value */
        public FHIRDeviceMetricCategory|string|null $value = null,
    ) {
    }
}
