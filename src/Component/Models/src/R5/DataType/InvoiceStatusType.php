<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\InvoiceStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type InvoiceStatus
 *
 * @description Code type wrapper for InvoiceStatus enum
 */
class InvoiceStatusType extends CodePrimitive
{
    public function __construct(
        /** @param InvoiceStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
