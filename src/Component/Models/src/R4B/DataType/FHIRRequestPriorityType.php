<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

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
        /** @param FHIRRequestPriority|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
