<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\EnrollmentOutcome;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type EnrollmentOutcome
 *
 * @description Code type wrapper for EnrollmentOutcome enum
 */
class EnrollmentOutcomeType extends CodePrimitive
{
    public function __construct(
        /** @param EnrollmentOutcome|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
