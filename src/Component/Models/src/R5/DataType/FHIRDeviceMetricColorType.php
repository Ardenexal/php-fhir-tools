<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDeviceMetricColor;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDeviceMetricColor
 *
 * @description Code type wrapper for FHIRDeviceMetricColor enum
 */
class FHIRDeviceMetricColorType extends FHIRCode
{
    public function __construct(
        /** @var FHIRDeviceMetricColor|string|null $value The code value */
        public FHIRDeviceMetricColor|string|null $value = null,
    ) {
    }
}
