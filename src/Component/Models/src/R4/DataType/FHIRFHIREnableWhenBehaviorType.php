<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIREnableWhenBehavior;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIREnableWhenBehavior
 *
 * @description Code type wrapper for FHIREnableWhenBehavior enum
 */
class FHIRFHIREnableWhenBehaviorType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIREnableWhenBehavior|string|null $value The code value */
        public FHIRFHIREnableWhenBehavior|string|null $value = null,
    ) {
    }
}
