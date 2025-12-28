<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRConstraintSeverity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConstraintSeverity
 *
 * @description Code type wrapper for FHIRConstraintSeverity enum
 */
class FHIRFHIRConstraintSeverityType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRConstraintSeverity|string|null $value The code value */
        public FHIRFHIRConstraintSeverity|string|null $value = null,
    ) {
    }
}
