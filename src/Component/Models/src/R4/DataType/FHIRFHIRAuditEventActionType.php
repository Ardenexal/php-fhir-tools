<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAuditEventAction;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRAuditEventAction
 *
 * @description Code type wrapper for FHIRAuditEventAction enum
 */
class FHIRFHIRAuditEventActionType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAuditEventAction|string|null $value The code value */
        public FHIRFHIRAuditEventAction|string|null $value = null,
    ) {
    }
}
