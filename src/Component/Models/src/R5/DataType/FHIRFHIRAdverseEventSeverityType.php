<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAdverseEventSeverity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAdverseEventSeverity
 *
 * @description Code type wrapper for FHIRAdverseEventSeverity enum
 */
class FHIRFHIRAdverseEventSeverityType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAdverseEventSeverity|string|null $value The code value */
        public FHIRFHIRAdverseEventSeverity|string|null $value = null,
    ) {
    }
}
