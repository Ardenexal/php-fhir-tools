<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\CountryValueSet;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type CountryValueSet
 *
 * @description Code type wrapper for CountryValueSet enum
 */
class CountryValueSetType extends CodePrimitive
{
    public function __construct(
        /** @param CountryValueSet|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
