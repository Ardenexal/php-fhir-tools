<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIREventTiming;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIREventTiming
 *
 * @description Code type wrapper for FHIREventTiming enum
 */
class FHIREventTimingType extends FHIRCode
{
    public function __construct(
        /** @var FHIREventTiming|string|null $value The code value */
        public FHIREventTiming|string|null $value = null,
    ) {
    }
}
