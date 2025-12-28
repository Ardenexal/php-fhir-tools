<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

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
        /** @var FHIRChargeItemStatus|string|null $value The code value */
        public FHIRChargeItemStatus|string|null $value = null,
    ) {
    }
}
