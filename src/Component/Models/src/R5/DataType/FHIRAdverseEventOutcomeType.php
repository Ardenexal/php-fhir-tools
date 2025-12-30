<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAdverseEventOutcome;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAdverseEventOutcome
 *
 * @description Code type wrapper for FHIRAdverseEventOutcome enum
 */
class FHIRAdverseEventOutcomeType extends FHIRCode
{
    public function __construct(
        /** @param FHIRAdverseEventOutcome|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
