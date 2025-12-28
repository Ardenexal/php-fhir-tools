<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

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
        /** @var FHIRAdverseEventSeverity|string|null $value The code value */
        public FHIRAdverseEventSeverity|string|null $value = null,
    ) {
    }
}
