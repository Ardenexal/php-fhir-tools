<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

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
        /** @var FHIRObservationRangeCategory|string|null $value The code value */
        public FHIRObservationRangeCategory|string|null $value = null,
    ) {
    }
}
