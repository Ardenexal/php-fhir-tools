<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRActionCardinalityBehavior;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRActionCardinalityBehavior
 *
 * @description Code type wrapper for FHIRActionCardinalityBehavior enum
 */
class FHIRActionCardinalityBehaviorType extends FHIRCode
{
    public function __construct(
        /** @var FHIRActionCardinalityBehavior|string|null $value The code value */
        public FHIRActionCardinalityBehavior|string|null $value = null,
    ) {
    }
}
