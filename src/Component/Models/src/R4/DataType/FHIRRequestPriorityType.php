<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRRequestPriority;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRRequestPriority
 *
 * @description Code type wrapper for FHIRRequestPriority enum
 */
class FHIRRequestPriorityType extends FHIRCode
{
    public function __construct(
        /** @var FHIRRequestPriority|string|null $value The code value */
        public FHIRRequestPriority|string|null $value = null,
    ) {
    }
}
