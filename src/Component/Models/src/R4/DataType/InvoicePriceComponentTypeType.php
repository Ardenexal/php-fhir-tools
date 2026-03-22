<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\InvoicePriceComponentType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type InvoicePriceComponentType
 *
 * @description Code type wrapper for InvoicePriceComponentType enum
 */
class InvoicePriceComponentTypeType extends CodePrimitive
{
    public function __construct(
        /** @param InvoicePriceComponentType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
