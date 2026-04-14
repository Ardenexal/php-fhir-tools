<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\DeviceDispenseStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type DeviceDispenseStatusCodes
 *
 * @description Code type wrapper for DeviceDispenseStatusCodes enum
 */
class DeviceDispenseStatusCodesType extends CodePrimitive
{
    public function __construct(
        /** @param DeviceDispenseStatusCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
