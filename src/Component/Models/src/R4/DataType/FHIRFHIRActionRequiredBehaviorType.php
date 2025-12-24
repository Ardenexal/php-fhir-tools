<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRActionRequiredBehavior;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRActionRequiredBehavior
 *
 * @description Code type wrapper for FHIRActionRequiredBehavior enum
 */
class FHIRFHIRActionRequiredBehaviorType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRActionRequiredBehavior|string|null $value The code value */
        public FHIRFHIRActionRequiredBehavior|string|null $value = null,
    ) {
    }
}
