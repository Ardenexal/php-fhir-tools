<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRWeekOfMonth;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRWeekOfMonth
 *
 * @description Code type wrapper for FHIRWeekOfMonth enum
 */
class FHIRFHIRWeekOfMonthType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRWeekOfMonth|string|null $value The code value */
        public FHIRFHIRWeekOfMonth|string|null $value = null,
    ) {
    }
}
