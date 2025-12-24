<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRDeviceUsageStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRDeviceUsageStatus
 *
 * @description Code type wrapper for FHIRDeviceUsageStatus enum
 */
class FHIRFHIRDeviceUsageStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRDeviceUsageStatus|string|null $value The code value */
        public FHIRFHIRDeviceUsageStatus|string|null $value = null,
    ) {
    }
}
