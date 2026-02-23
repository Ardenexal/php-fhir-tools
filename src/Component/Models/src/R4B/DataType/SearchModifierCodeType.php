<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\SearchModifierCode;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type SearchModifierCode
 *
 * @description Code type wrapper for SearchModifierCode enum
 */
class SearchModifierCodeType extends CodePrimitive
{
    public function __construct(
        /** @param SearchModifierCode|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
