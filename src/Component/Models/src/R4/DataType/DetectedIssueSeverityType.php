<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\DetectedIssueSeverity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type DetectedIssueSeverity
 *
 * @description Code type wrapper for DetectedIssueSeverity enum
 */
class DetectedIssueSeverityType extends CodePrimitive
{
    public function __construct(
        /** @param DetectedIssueSeverity|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
