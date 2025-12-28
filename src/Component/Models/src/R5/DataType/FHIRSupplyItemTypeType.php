<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRSupplyItemType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSupplyItemType
 *
 * @description Code type wrapper for FHIRSupplyItemType enum
 */
class FHIRSupplyItemTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRSupplyItemType|string|null $value The code value */
        public FHIRSupplyItemType|string|null $value = null,
    ) {
    }
}
