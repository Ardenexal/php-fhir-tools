<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAddressType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAddressType
 *
 * @description Code type wrapper for FHIRAddressType enum
 */
class FHIRAddressTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRAddressType|string|null $value The code value */
        public FHIRAddressType|string|null $value = null,
    ) {
    }
}
