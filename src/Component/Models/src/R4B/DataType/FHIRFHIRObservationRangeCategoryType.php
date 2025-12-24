<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRObservationRangeCategory;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRObservationRangeCategory
 *
 * @description Code type wrapper for FHIRObservationRangeCategory enum
 */
class FHIRFHIRObservationRangeCategoryType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRObservationRangeCategory|string|null $value The code value */
        public FHIRFHIRObservationRangeCategory|string|null $value = null,
    ) {
    }
}
