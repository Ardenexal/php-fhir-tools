<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSupplyItemType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSupplyItemType
 *
 * @description Code type wrapper for FHIRSupplyItemType enum
 */
class FHIRFHIRSupplyItemTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRSupplyItemType|string|null $value The code value */
        public FHIRFHIRSupplyItemType|string|null $value = null,
    ) {
    }
}
