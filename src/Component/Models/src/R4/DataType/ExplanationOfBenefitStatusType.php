<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\ExplanationOfBenefitStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type ExplanationOfBenefitStatus
 *
 * @description Code type wrapper for ExplanationOfBenefitStatus enum
 */
class ExplanationOfBenefitStatusType extends CodePrimitive
{
    public function __construct(
        /** @param ExplanationOfBenefitStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
