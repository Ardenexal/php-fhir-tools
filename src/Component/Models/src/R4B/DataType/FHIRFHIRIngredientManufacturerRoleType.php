<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRIngredientManufacturerRole;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRIngredientManufacturerRole
 *
 * @description Code type wrapper for FHIRIngredientManufacturerRole enum
 */
class FHIRFHIRIngredientManufacturerRoleType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRIngredientManufacturerRole|string|null $value The code value */
        public FHIRFHIRIngredientManufacturerRole|string|null $value = null,
    ) {
    }
}
