<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FilterOperator;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type FilterOperator
 *
 * @description Code type wrapper for FilterOperator enum
 */
class FilterOperatorType extends CodePrimitive
{
    public function __construct(
        /** @param FilterOperator|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
