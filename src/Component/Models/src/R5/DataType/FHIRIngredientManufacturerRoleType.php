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
        /** @param FHIRIngredientManufacturerRole|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
