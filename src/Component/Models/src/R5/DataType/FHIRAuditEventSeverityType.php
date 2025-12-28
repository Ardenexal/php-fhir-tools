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
        /** @var FHIRAuditEventSeverity|string|null $value The code value */
        public FHIRAuditEventSeverity|string|null $value = null,
    ) {
    }
}
