<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\RemittanceOutcome;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type RemittanceOutcome
 *
 * @description Code type wrapper for RemittanceOutcome enum
 */
class RemittanceOutcomeType extends CodePrimitive
{
    public function __construct(
        /** @param RemittanceOutcome|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
