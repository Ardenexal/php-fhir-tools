<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRChargeItemStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRChargeItemStatus
 *
 * @description Code type wrapper for FHIRChargeItemStatus enum
 */
class FHIRChargeItemStatusType extends FHIRCode
{
    public function __construct(
        /** @param FHIRChargeItemStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
