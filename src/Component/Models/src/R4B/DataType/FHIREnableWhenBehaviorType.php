<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIREnableWhenBehavior;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIREnableWhenBehavior
 *
 * @description Code type wrapper for FHIREnableWhenBehavior enum
 */
class FHIREnableWhenBehaviorType extends FHIRCode
{
    public function __construct(
        /** @param FHIREnableWhenBehavior|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
