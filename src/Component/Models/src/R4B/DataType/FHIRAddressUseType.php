<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAddressUse;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAddressUse
 *
 * @description Code type wrapper for FHIRAddressUse enum
 */
class FHIRAddressUseType extends FHIRCode
{
    public function __construct(
        /** @var FHIRAddressUse|string|null $value The code value */
        public FHIRAddressUse|string|null $value = null,
    ) {
    }
}
