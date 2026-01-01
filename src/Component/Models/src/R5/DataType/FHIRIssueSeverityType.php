<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRIssueSeverity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRIssueSeverity
 *
 * @description Code type wrapper for FHIRIssueSeverity enum
 */
class FHIRIssueSeverityType extends FHIRCode
{
    public function __construct(
        /** @param FHIRIssueSeverity|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
