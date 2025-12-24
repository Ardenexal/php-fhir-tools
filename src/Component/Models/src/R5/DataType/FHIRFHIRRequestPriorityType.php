<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRRequestPriority;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRRequestPriority
 *
 * @description Code type wrapper for FHIRRequestPriority enum
 */
class FHIRFHIRRequestPriorityType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRRequestPriority|string|null $value The code value */
        public FHIRFHIRRequestPriority|string|null $value = null,
    ) {
    }
}
