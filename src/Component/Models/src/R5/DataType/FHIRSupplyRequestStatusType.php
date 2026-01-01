<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

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
        /** @param FHIRSupplyRequestStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
