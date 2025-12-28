<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRActionSelectionBehavior;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRActionSelectionBehavior
 *
 * @description Code type wrapper for FHIRActionSelectionBehavior enum
 */
class FHIRFHIRActionSelectionBehaviorType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRActionSelectionBehavior|string|null $value The code value */
        public FHIRFHIRActionSelectionBehavior|string|null $value = null,
    ) {
    }
}
