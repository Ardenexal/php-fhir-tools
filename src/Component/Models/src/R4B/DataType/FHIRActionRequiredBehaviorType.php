<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRActionRequiredBehavior;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRActionRequiredBehavior
 *
 * @description Code type wrapper for FHIRActionRequiredBehavior enum
 */
class FHIRActionRequiredBehaviorType extends FHIRCode
{
    public function __construct(
        /** @var FHIRActionRequiredBehavior|string|null $value The code value */
        public FHIRActionRequiredBehavior|string|null $value = null,
    ) {
    }
}
