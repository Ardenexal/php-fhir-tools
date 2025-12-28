<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDeviceNameType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDeviceNameType
 *
 * @description Code type wrapper for FHIRDeviceNameType enum
 */
class FHIRFHIRDeviceNameTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRDeviceNameType|string|null $value The code value */
        public FHIRFHIRDeviceNameType|string|null $value = null,
    ) {
    }
}
