<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRDeviceUsageStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDeviceUsageStatus
 *
 * @description Code type wrapper for FHIRDeviceUsageStatus enum
 */
class FHIRDeviceUsageStatusType extends FHIRCode
{
    public function __construct(
        /** @param FHIRDeviceUsageStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
