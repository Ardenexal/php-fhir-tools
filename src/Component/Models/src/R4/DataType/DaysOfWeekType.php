<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\DaysOfWeek;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type DaysOfWeek
 *
 * @description Code type wrapper for DaysOfWeek enum
 */
class DaysOfWeekType extends CodePrimitive
{
    public function __construct(
        /** @param DaysOfWeek|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
