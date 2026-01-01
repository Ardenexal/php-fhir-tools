<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

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
        /** @param FHIRDeviceMetricOperationalStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
