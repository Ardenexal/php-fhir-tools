<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRBindingStrength;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRBindingStrength
 *
 * @description Code type wrapper for FHIRBindingStrength enum
 */
class FHIRBindingStrengthType extends FHIRCode
{
    public function __construct(
        /** @param FHIRBindingStrength|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
