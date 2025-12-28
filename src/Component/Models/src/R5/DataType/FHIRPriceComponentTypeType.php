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
        /** @var FHIRPriceComponentType|string|null $value The code value */
        public FHIRPriceComponentType|string|null $value = null,
    ) {
    }
}
