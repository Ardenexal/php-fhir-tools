<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSearchModifierCode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSearchModifierCode
 *
 * @description Code type wrapper for FHIRSearchModifierCode enum
 */
class FHIRFHIRSearchModifierCodeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRSearchModifierCode|string|null $value The code value */
        public FHIRFHIRSearchModifierCode|string|null $value = null,
    ) {
    }
}
