<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\AuditEventSeverity;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type AuditEventSeverity
 *
 * @description Code type wrapper for AuditEventSeverity enum
 */
class AuditEventSeverityType extends CodePrimitive
{
    public function __construct(
        /** @param AuditEventSeverity|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
