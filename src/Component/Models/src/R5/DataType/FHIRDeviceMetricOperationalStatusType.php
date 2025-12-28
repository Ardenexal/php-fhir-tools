<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDeviceMetricOperationalStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDeviceMetricOperationalStatus
 *
 * @description Code type wrapper for FHIRDeviceMetricOperationalStatus enum
 */
class FHIRDeviceMetricOperationalStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRDeviceMetricOperationalStatus|string|null $value The code value */
        public FHIRDeviceMetricOperationalStatus|string|null $value = null,
    ) {
    }
}
