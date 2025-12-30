<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

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
        /** @param FHIRInvoiceStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
