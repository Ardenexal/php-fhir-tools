<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\DeviceNameType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type DeviceNameType
 *
 * @description Code type wrapper for DeviceNameType enum
 */
class DeviceNameTypeType extends CodePrimitive
{
    public function __construct(
        /** @param DeviceNameType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
