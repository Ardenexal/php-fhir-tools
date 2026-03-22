<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\AdverseEventOutcome;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

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
