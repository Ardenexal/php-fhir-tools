<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDeviceNameType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDeviceNameType
 *
 * @description Code type wrapper for FHIRDeviceNameType enum
 */
class FHIRDeviceNameTypeType extends FHIRCode
{
    public function __construct(
        /** @param FHIRDeviceNameType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
