<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\EligibilityOutcome;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type EligibilityOutcome
 *
 * @description Code type wrapper for EligibilityOutcome enum
 */
class EligibilityOutcomeType extends CodePrimitive
{
    public function __construct(
        /** @param EligibilityOutcome|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
