<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRFHIRDeviceStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRFHIRDeviceStatus
 *
 * @description Code type wrapper for FHIRFHIRDeviceStatus enum
 */
class FHIRFHIRFHIRDeviceStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRFHIRDeviceStatus|string|null $value The code value */
        public FHIRFHIRFHIRDeviceStatus|string|null $value = null,
    ) {
    }
}
