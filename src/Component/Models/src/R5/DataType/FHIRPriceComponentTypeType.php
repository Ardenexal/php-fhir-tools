<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRPriceComponentType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRPriceComponentType
 *
 * @description Code type wrapper for FHIRPriceComponentType enum
 */
class FHIRPriceComponentTypeType extends FHIRCode
{
    public function __construct(
        /** @param FHIRPriceComponentType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
