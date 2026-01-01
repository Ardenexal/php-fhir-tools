<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRCountryValueSet;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCountryValueSet
 *
 * @description Code type wrapper for FHIRCountryValueSet enum
 */
class FHIRCountryValueSetType extends FHIRCode
{
    public function __construct(
        /** @param FHIRCountryValueSet|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
