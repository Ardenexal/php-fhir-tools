<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDeviceStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRFHIRDeviceStatus
 *
 * @description Code type wrapper for FHIRFHIRDeviceStatus enum
 */
class FHIRFHIRDeviceStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRDeviceStatus|string|null $value The code value */
        public FHIRFHIRDeviceStatus|string|null $value = null,
    ) {
    }
}
