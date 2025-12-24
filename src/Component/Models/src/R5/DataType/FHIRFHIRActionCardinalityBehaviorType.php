<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRActionCardinalityBehavior;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRActionCardinalityBehavior
 *
 * @description Code type wrapper for FHIRActionCardinalityBehavior enum
 */
class FHIRFHIRActionCardinalityBehaviorType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRActionCardinalityBehavior|string|null $value The code value */
        public FHIRFHIRActionCardinalityBehavior|string|null $value = null,
    ) {
    }
}
