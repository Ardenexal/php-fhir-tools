<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRListMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRListMode
 *
 * @description Code type wrapper for FHIRListMode enum
 */
class FHIRListModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRListMode|string|null $value The code value */
        public FHIRListMode|string|null $value = null,
    ) {
    }
}
