<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRCodeSystemContentMode;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRCodeSystemContentMode
 *
 * @description Code type wrapper for FHIRCodeSystemContentMode enum
 */
class FHIRFHIRCodeSystemContentModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRCodeSystemContentMode|string|null $value The code value */
        public FHIRFHIRCodeSystemContentMode|string|null $value = null,
    ) {
    }
}
