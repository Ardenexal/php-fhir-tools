<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRSupplyDeliverySupplyItemType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSupplyDeliverySupplyItemType
 *
 * @description Code type wrapper for FHIRSupplyDeliverySupplyItemType enum
 */
class FHIRSupplyDeliverySupplyItemTypeType extends FHIRCode
{
    public function __construct(
        /** @param FHIRSupplyDeliverySupplyItemType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
