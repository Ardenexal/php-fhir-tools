<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRSearchEntryMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSearchEntryMode
 *
 * @description Code type wrapper for FHIRSearchEntryMode enum
 */
class FHIRSearchEntryModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRSearchEntryMode|string|null $value The code value */
        public FHIRSearchEntryMode|string|null $value = null,
    ) {
    }
}
