<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\AuditEventAction;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type AuditEventAction
 *
 * @description Code type wrapper for AuditEventAction enum
 */
class AuditEventActionType extends CodePrimitive
{
    public function __construct(
        /** @param AuditEventAction|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
