<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\DetectedIssueSeverity;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

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
