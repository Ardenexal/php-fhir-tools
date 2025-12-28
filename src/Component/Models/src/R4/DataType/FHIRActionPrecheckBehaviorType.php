<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRActionPrecheckBehavior;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRActionPrecheckBehavior
 *
 * @description Code type wrapper for FHIRActionPrecheckBehavior enum
 */
class FHIRActionPrecheckBehaviorType extends FHIRCode
{
    public function __construct(
        /** @var FHIRActionPrecheckBehavior|string|null $value The code value */
        public FHIRActionPrecheckBehavior|string|null $value = null,
    ) {
    }
}
