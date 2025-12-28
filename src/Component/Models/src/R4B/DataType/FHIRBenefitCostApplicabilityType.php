<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRBenefitCostApplicability;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRBenefitCostApplicability
 *
 * @description Code type wrapper for FHIRBenefitCostApplicability enum
 */
class FHIRBenefitCostApplicabilityType extends FHIRCode
{
    public function __construct(
        /** @var FHIRBenefitCostApplicability|string|null $value The code value */
        public FHIRBenefitCostApplicability|string|null $value = null,
    ) {
    }
}
