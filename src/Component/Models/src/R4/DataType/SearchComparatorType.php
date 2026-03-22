<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\SearchComparator;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type SearchComparator
 *
 * @description Code type wrapper for SearchComparator enum
 */
class SearchComparatorType extends CodePrimitive
{
    public function __construct(
        /** @param SearchComparator|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
