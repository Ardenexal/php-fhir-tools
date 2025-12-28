<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRIngredientManufacturerRole;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRIngredientManufacturerRole
 *
 * @description Code type wrapper for FHIRIngredientManufacturerRole enum
 */
class FHIRIngredientManufacturerRoleType extends FHIRCode
{
    public function __construct(
        /** @var FHIRIngredientManufacturerRole|string|null $value The code value */
        public FHIRIngredientManufacturerRole|string|null $value = null,
    ) {
    }
}
