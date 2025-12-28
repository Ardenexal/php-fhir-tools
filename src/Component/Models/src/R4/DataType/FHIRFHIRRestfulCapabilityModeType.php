<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRRestfulCapabilityMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRRestfulCapabilityMode
 *
 * @description Code type wrapper for FHIRRestfulCapabilityMode enum
 */
class FHIRFHIRRestfulCapabilityModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRRestfulCapabilityMode|string|null $value The code value */
        public FHIRFHIRRestfulCapabilityMode|string|null $value = null,
    ) {
    }
}
