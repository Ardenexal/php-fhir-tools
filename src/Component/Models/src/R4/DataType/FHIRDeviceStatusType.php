<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDeviceStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type FHIRDeviceStatus
 *
 * @description Code type wrapper for FHIRDeviceStatus enum
 */
class FHIRDeviceStatusType extends CodePrimitive
{
    public function __construct(
        /** @param FHIRDeviceStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
