<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRBenefitCostApplicability;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRBenefitCostApplicability
 *
 * @description Code type wrapper for FHIRBenefitCostApplicability enum
 */
class FHIRFHIRBenefitCostApplicabilityType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRBenefitCostApplicability|string|null $value The code value */
        public FHIRFHIRBenefitCostApplicability|string|null $value = null,
    ) {
    }
}
