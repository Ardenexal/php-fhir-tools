<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\SupplyDeliveryStatus;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type SupplyDeliveryStatus
 *
 * @description Code type wrapper for SupplyDeliveryStatus enum
 */
class SupplyDeliveryStatusType extends CodePrimitive
{
    public function __construct(
        /** @param SupplyDeliveryStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
