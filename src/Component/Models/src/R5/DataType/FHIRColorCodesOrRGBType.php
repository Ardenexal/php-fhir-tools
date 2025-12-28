<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRColorCodesOrRGB;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRColorCodesOrRGB
 *
 * @description Code type wrapper for FHIRColorCodesOrRGB enum
 */
class FHIRColorCodesOrRGBType extends FHIRCode
{
    public function __construct(
        /** @var FHIRColorCodesOrRGB|string|null $value The code value */
        public FHIRColorCodesOrRGB|string|null $value = null,
    ) {
    }
}
