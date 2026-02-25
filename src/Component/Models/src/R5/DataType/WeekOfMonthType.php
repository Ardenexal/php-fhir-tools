<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\WeekOfMonth;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type WeekOfMonth
 *
 * @description Code type wrapper for WeekOfMonth enum
 */
class WeekOfMonthType extends CodePrimitive
{
    public function __construct(
        /** @param WeekOfMonth|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
