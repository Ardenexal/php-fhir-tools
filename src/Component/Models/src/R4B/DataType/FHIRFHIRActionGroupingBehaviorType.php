<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRActionGroupingBehavior;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRActionGroupingBehavior
 *
 * @description Code type wrapper for FHIRActionGroupingBehavior enum
 */
class FHIRFHIRActionGroupingBehaviorType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRActionGroupingBehavior|string|null $value The code value */
        public FHIRFHIRActionGroupingBehavior|string|null $value = null,
    ) {
    }
}
