<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRSPDXLicense;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSPDXLicense
 *
 * @description Code type wrapper for FHIRSPDXLicense enum
 */
class FHIRSPDXLicenseType extends FHIRCode
{
    public function __construct(
        /** @var FHIRSPDXLicense|string|null $value The code value */
        public FHIRSPDXLicense|string|null $value = null,
    ) {
    }
}
