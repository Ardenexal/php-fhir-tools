<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\NutritionProductStatus;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type NutritionProductStatus
 *
 * @description Code type wrapper for NutritionProductStatus enum
 */
class NutritionProductStatusType extends CodePrimitive
{
    public function __construct(
        /** @param NutritionProductStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
