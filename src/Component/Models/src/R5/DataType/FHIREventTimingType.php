<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

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
        /** @param FHIREventTiming|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
