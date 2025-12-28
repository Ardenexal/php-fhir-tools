<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRColorCodesOrRGB;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRColorCodesOrRGB
 *
 * @description Code type wrapper for FHIRColorCodesOrRGB enum
 */
class FHIRFHIRColorCodesOrRGBType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRColorCodesOrRGB|string|null $value The code value */
        public FHIRFHIRColorCodesOrRGB|string|null $value = null,
    ) {
    }
}
