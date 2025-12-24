<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRChargeItemStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRChargeItemStatus
 *
 * @description Code type wrapper for FHIRChargeItemStatus enum
 */
class FHIRFHIRChargeItemStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRChargeItemStatus|string|null $value The code value */
        public FHIRFHIRChargeItemStatus|string|null $value = null,
    ) {
    }
}
