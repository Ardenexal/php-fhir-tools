<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\BiologicallyDerivedProductCategory;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type BiologicallyDerivedProductCategory
 *
 * @description Code type wrapper for BiologicallyDerivedProductCategory enum
 */
class BiologicallyDerivedProductCategoryType extends CodePrimitive
{
    public function __construct(
        /** @param BiologicallyDerivedProductCategory|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
