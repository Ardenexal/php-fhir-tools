<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIREventCapabilityMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIREventCapabilityMode
 *
 * @description Code type wrapper for FHIREventCapabilityMode enum
 */
class FHIRFHIREventCapabilityModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIREventCapabilityMode|string|null $value The code value */
        public FHIRFHIREventCapabilityMode|string|null $value = null,
    ) {
    }
}
