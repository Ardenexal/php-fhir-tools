<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRNutritionProductStatus;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRNutritionProductStatus
 *
 * @description Code type wrapper for FHIRNutritionProductStatus enum
 */
class FHIRFHIRNutritionProductStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRNutritionProductStatus|string|null $value The code value */
        public FHIRFHIRNutritionProductStatus|string|null $value = null,
    ) {
    }
}
