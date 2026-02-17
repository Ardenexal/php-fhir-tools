<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\AdverseEventOutcome;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type AdverseEventOutcome
 *
 * @description Code type wrapper for AdverseEventOutcome enum
 */
class AdverseEventOutcomeType extends CodePrimitive
{
    public function __construct(
        /** @param AdverseEventOutcome|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
