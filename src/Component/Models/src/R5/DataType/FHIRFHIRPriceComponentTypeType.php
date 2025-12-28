<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRPriceComponentType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRPriceComponentType
 *
 * @description Code type wrapper for FHIRPriceComponentType enum
 */
class FHIRFHIRPriceComponentTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRPriceComponentType|string|null $value The code value */
        public FHIRFHIRPriceComponentType|string|null $value = null,
    ) {
    }
}
