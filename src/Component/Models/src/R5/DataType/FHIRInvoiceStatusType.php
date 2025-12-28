<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRInvoiceStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRInvoiceStatus
 *
 * @description Code type wrapper for FHIRInvoiceStatus enum
 */
class FHIRInvoiceStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRInvoiceStatus|string|null $value The code value */
        public FHIRInvoiceStatus|string|null $value = null,
    ) {
    }
}
