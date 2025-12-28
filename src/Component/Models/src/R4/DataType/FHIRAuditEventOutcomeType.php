<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAuditEventOutcome;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAuditEventOutcome
 *
 * @description Code type wrapper for FHIRAuditEventOutcome enum
 */
class FHIRAuditEventOutcomeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRAuditEventOutcome|string|null $value The code value */
        public FHIRAuditEventOutcome|string|null $value = null,
    ) {
    }
}
