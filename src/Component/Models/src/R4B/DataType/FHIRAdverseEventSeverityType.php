<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAdverseEventSeverity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAdverseEventSeverity
 *
 * @description Code type wrapper for FHIRAdverseEventSeverity enum
 */
class FHIRAdverseEventSeverityType extends FHIRCode
{
    public function __construct(
        /** @param FHIRAdverseEventSeverity|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
