<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRConstraintSeverity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConstraintSeverity
 *
 * @description Code type wrapper for FHIRConstraintSeverity enum
 */
class FHIRConstraintSeverityType extends FHIRCode
{
    public function __construct(
        /** @var FHIRConstraintSeverity|string|null $value The code value */
        public FHIRConstraintSeverity|string|null $value = null,
    ) {
    }
}
