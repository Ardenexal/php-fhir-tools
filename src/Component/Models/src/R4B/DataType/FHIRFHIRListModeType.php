<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRListMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRListMode
 *
 * @description Code type wrapper for FHIRListMode enum
 */
class FHIRFHIRListModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRListMode|string|null $value The code value */
        public FHIRFHIRListMode|string|null $value = null,
    ) {
    }
}
