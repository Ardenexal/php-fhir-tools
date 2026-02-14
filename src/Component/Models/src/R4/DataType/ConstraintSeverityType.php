<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\ConstraintSeverity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type ConstraintSeverity
 *
 * @description Code type wrapper for ConstraintSeverity enum
 */
class ConstraintSeverityType extends CodePrimitive
{
    public function __construct(
        /** @param ConstraintSeverity|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
