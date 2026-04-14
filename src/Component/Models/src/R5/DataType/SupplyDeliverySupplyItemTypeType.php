<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\SupplyDeliverySupplyItemType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type SupplyDeliverySupplyItemType
 *
 * @description Code type wrapper for SupplyDeliverySupplyItemType enum
 */
class SupplyDeliverySupplyItemTypeType extends CodePrimitive
{
    public function __construct(
        /** @param SupplyDeliverySupplyItemType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
