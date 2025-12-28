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
        /** @var FHIRSupplyDeliverySupplyItemType|string|null $value The code value */
        public FHIRSupplyDeliverySupplyItemType|string|null $value = null,
    ) {
    }
}
