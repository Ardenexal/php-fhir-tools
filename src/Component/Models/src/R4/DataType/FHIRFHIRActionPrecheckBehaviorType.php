<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRActionPrecheckBehavior;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRActionPrecheckBehavior
 *
 * @description Code type wrapper for FHIRActionPrecheckBehavior enum
 */
class FHIRFHIRActionPrecheckBehaviorType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRActionPrecheckBehavior|string|null $value The code value */
        public FHIRFHIRActionPrecheckBehavior|string|null $value = null,
    ) {
    }
}
