<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDeviceMetricColor;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRDeviceMetricColor
 *
 * @description Code type wrapper for FHIRDeviceMetricColor enum
 */
class FHIRFHIRDeviceMetricColorType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRDeviceMetricColor|string|null $value The code value */
        public FHIRFHIRDeviceMetricColor|string|null $value = null,
    ) {
    }
}
