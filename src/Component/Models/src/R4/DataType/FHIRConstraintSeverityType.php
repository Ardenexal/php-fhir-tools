<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

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
        /** @param FHIRConstraintSeverity|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
