<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAddressUse;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRAddressUse
 *
 * @description Code type wrapper for FHIRAddressUse enum
 */
class FHIRFHIRAddressUseType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAddressUse|string|null $value The code value */
        public FHIRFHIRAddressUse|string|null $value = null,
    ) {
    }
}
