<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\PriceComponentType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type PriceComponentType
 *
 * @description Code type wrapper for PriceComponentType enum
 */
class PriceComponentTypeType extends CodePrimitive
{
    public function __construct(
        /** @param PriceComponentType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
