<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRWeekOfMonth;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRWeekOfMonth
 *
 * @description Code type wrapper for FHIRWeekOfMonth enum
 */
class FHIRWeekOfMonthType extends FHIRCode
{
    public function __construct(
        /** @var FHIRWeekOfMonth|string|null $value The code value */
        public FHIRWeekOfMonth|string|null $value = null,
    ) {
    }
}
