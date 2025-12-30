<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRSupplyDeliveryStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSupplyDeliveryStatus
 *
 * @description Code type wrapper for FHIRSupplyDeliveryStatus enum
 */
class FHIRSupplyDeliveryStatusType extends FHIRCode
{
    public function __construct(
        /** @param FHIRSupplyDeliveryStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
