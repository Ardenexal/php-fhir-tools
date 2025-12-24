<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDeviceMetricCategory;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRDeviceMetricCategory
 *
 * @description Code type wrapper for FHIRDeviceMetricCategory enum
 */
class FHIRFHIRDeviceMetricCategoryType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRDeviceMetricCategory|string|null $value The code value */
        public FHIRFHIRDeviceMetricCategory|string|null $value = null,
    ) {
    }
}
