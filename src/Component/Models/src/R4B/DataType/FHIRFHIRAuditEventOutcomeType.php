<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAuditEventOutcome;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAuditEventOutcome
 *
 * @description Code type wrapper for FHIRAuditEventOutcome enum
 */
class FHIRFHIRAuditEventOutcomeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAuditEventOutcome|string|null $value The code value */
        public FHIRFHIRAuditEventOutcome|string|null $value = null,
    ) {
    }
}
