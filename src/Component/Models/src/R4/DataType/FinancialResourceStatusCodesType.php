<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FinancialResourceStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type FinancialResourceStatusCodes
 *
 * @description Code type wrapper for FinancialResourceStatusCodes enum
 */
class FinancialResourceStatusCodesType extends CodePrimitive
{
    public function __construct(
        /** @param FinancialResourceStatusCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
