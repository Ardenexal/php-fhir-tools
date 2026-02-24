<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\BindingStrength;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type BindingStrength
 *
 * @description Code type wrapper for BindingStrength enum
 */
class BindingStrengthType extends CodePrimitive
{
    public function __construct(
        /** @param BindingStrength|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
