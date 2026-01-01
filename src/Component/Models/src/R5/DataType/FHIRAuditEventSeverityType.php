<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRAuditEventSeverity;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAuditEventSeverity
 *
 * @description Code type wrapper for FHIRAuditEventSeverity enum
 */
class FHIRAuditEventSeverityType extends FHIRCode
{
    public function __construct(
        /** @param FHIRAuditEventSeverity|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
