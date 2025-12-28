<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRSupplyRequestStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSupplyRequestStatus
 *
 * @description Code type wrapper for FHIRSupplyRequestStatus enum
 */
class FHIRSupplyRequestStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRSupplyRequestStatus|string|null $value The code value */
        public FHIRSupplyRequestStatus|string|null $value = null,
    ) {
    }
}
