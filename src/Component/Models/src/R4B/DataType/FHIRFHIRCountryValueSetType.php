<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRCountryValueSet;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCountryValueSet
 *
 * @description Code type wrapper for FHIRCountryValueSet enum
 */
class FHIRFHIRCountryValueSetType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRCountryValueSet|string|null $value The code value */
        public FHIRFHIRCountryValueSet|string|null $value = null,
    ) {
    }
}
