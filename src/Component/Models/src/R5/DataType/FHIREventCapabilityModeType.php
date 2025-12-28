<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIREventCapabilityMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIREventCapabilityMode
 *
 * @description Code type wrapper for FHIREventCapabilityMode enum
 */
class FHIREventCapabilityModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIREventCapabilityMode|string|null $value The code value */
        public FHIREventCapabilityMode|string|null $value = null,
    ) {
    }
}
