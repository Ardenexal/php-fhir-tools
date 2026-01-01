<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRObservationRangeCategory;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRObservationRangeCategory
 *
 * @description Code type wrapper for FHIRObservationRangeCategory enum
 */
class FHIRObservationRangeCategoryType extends FHIRCode
{
    public function __construct(
        /** @param FHIRObservationRangeCategory|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
