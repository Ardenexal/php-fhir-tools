<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRBindingStrength;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRBindingStrength
 *
 * @description Code type wrapper for FHIRBindingStrength enum
 */
class FHIRFHIRBindingStrengthType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRBindingStrength|string|null $value The code value */
        public FHIRFHIRBindingStrength|string|null $value = null,
    ) {
    }
}
