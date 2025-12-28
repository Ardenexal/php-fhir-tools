<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRCodeSystemContentMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCodeSystemContentMode
 *
 * @description Code type wrapper for FHIRCodeSystemContentMode enum
 */
class FHIRCodeSystemContentModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRCodeSystemContentMode|string|null $value The code value */
        public FHIRCodeSystemContentMode|string|null $value = null,
    ) {
    }
}
