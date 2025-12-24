<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRAuditEventSeverity;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRAuditEventSeverity
 *
 * @description Code type wrapper for FHIRAuditEventSeverity enum
 */
class FHIRFHIRAuditEventSeverityType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAuditEventSeverity|string|null $value The code value */
        public FHIRFHIRAuditEventSeverity|string|null $value = null,
    ) {
    }
}
