<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRInvoiceStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRInvoiceStatus
 *
 * @description Code type wrapper for FHIRInvoiceStatus enum
 */
class FHIRFHIRInvoiceStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRInvoiceStatus|string|null $value The code value */
        public FHIRFHIRInvoiceStatus|string|null $value = null,
    ) {
    }
}
