<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIREventTiming;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIREventTiming
 *
 * @description Code type wrapper for FHIREventTiming enum
 */
class FHIRFHIREventTimingType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIREventTiming|string|null $value The code value */
        public FHIRFHIREventTiming|string|null $value = null,
    ) {
    }
}
