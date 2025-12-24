<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAddressType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRAddressType
 *
 * @description Code type wrapper for FHIRAddressType enum
 */
class FHIRFHIRAddressTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAddressType|string|null $value The code value */
        public FHIRFHIRAddressType|string|null $value = null,
    ) {
    }
}
