<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSearchEntryMode;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRSearchEntryMode
 *
 * @description Code type wrapper for FHIRSearchEntryMode enum
 */
class FHIRFHIRSearchEntryModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRSearchEntryMode|string|null $value The code value */
        public FHIRFHIRSearchEntryMode|string|null $value = null,
    ) {
    }
}
