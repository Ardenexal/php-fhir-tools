<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\ColorCodesOrRGB;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type ColorCodesOrRGB
 *
 * @description Code type wrapper for ColorCodesOrRGB enum
 */
class ColorCodesOrRGBType extends CodePrimitive
{
    public function __construct(
        /** @param ColorCodesOrRGB|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
