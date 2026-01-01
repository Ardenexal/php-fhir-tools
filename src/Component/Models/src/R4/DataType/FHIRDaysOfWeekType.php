<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDaysOfWeek;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDaysOfWeek
 *
 * @description Code type wrapper for FHIRDaysOfWeek enum
 */
class FHIRDaysOfWeekType extends FHIRCode
{
    public function __construct(
        /** @param FHIRDaysOfWeek|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
