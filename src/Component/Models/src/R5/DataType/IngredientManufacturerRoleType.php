<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\IngredientManufacturerRole;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type IngredientManufacturerRole
 *
 * @description Code type wrapper for IngredientManufacturerRole enum
 */
class IngredientManufacturerRoleType extends CodePrimitive
{
    public function __construct(
        /** @param IngredientManufacturerRole|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
