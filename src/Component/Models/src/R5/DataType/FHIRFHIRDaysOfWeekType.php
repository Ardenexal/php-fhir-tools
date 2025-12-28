<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDaysOfWeek;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDaysOfWeek
 *
 * @description Code type wrapper for FHIRDaysOfWeek enum
 */
class FHIRFHIRDaysOfWeekType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRDaysOfWeek|string|null $value The code value */
        public FHIRFHIRDaysOfWeek|string|null $value = null,
    ) {
    }
}
