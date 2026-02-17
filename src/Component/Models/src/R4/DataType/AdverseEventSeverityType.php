<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\AdverseEventSeverity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type AdverseEventSeverity
 *
 * @description Code type wrapper for AdverseEventSeverity enum
 */
class AdverseEventSeverityType extends CodePrimitive
{
    public function __construct(
        /** @param AdverseEventSeverity|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
