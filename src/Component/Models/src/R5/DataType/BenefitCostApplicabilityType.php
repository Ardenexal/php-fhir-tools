<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\BenefitCostApplicability;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type BenefitCostApplicability
 *
 * @description Code type wrapper for BenefitCostApplicability enum
 */
class BenefitCostApplicabilityType extends CodePrimitive
{
    public function __construct(
        /** @param BenefitCostApplicability|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
