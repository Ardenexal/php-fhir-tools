<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\UCUMCodes;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type UCUMCodes
 *
 * @description Code type wrapper for UCUMCodes enum
 */
class UCUMCodesType extends CodePrimitive
{
    public function __construct(
        /** @param UCUMCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
