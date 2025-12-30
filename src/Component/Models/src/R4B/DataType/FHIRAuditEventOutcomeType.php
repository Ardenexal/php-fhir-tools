<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

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
        /** @param FHIRAuditEventOutcome|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
