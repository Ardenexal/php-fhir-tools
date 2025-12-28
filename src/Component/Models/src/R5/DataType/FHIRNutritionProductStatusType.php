<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRNutritionProductStatus;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRNutritionProductStatus
 *
 * @description Code type wrapper for FHIRNutritionProductStatus enum
 */
class FHIRNutritionProductStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRNutritionProductStatus|string|null $value The code value */
        public FHIRNutritionProductStatus|string|null $value = null,
    ) {
    }
}
