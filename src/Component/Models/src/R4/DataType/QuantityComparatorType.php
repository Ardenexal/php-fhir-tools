<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\QuantityComparator;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type QuantityComparator
 *
 * @description Code type wrapper for QuantityComparator enum
 */
class QuantityComparatorType extends CodePrimitive
{
    public function __construct(
        /** @param QuantityComparator|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
