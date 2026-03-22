<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\ObservationRangeCategory;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type ObservationRangeCategory
 *
 * @description Code type wrapper for ObservationRangeCategory enum
 */
class ObservationRangeCategoryType extends CodePrimitive
{
    public function __construct(
        /** @param ObservationRangeCategory|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
