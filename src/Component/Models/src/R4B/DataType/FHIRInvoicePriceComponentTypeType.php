<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRInvoicePriceComponentType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRInvoicePriceComponentType
 *
 * @description Code type wrapper for FHIRInvoicePriceComponentType enum
 */
class FHIRInvoicePriceComponentTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRInvoicePriceComponentType|string|null $value The code value */
        public FHIRInvoicePriceComponentType|string|null $value = null,
    ) {
    }
}
