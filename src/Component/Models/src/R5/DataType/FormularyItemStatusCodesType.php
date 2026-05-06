<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FormularyItemStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type FormularyItemStatusCodes
 *
 * @description Code type wrapper for FormularyItemStatusCodes enum
 */
class FormularyItemStatusCodesType extends CodePrimitive
{
    public function __construct(
        /** @param FormularyItemStatusCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
