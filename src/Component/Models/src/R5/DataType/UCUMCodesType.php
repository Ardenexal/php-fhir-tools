<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\UCUMCodes;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

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
