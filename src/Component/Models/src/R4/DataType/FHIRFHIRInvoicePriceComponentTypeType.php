<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRInvoicePriceComponentType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRInvoicePriceComponentType
 *
 * @description Code type wrapper for FHIRInvoicePriceComponentType enum
 */
class FHIRFHIRInvoicePriceComponentTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRInvoicePriceComponentType|string|null $value The code value */
        public FHIRFHIRInvoicePriceComponentType|string|null $value = null,
    ) {
    }
}
