<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRExplanationOfBenefitStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRExplanationOfBenefitStatus
 *
 * @description Code type wrapper for FHIRExplanationOfBenefitStatus enum
 */
class FHIRExplanationOfBenefitStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRExplanationOfBenefitStatus|string|null $value The code value */
        public FHIRExplanationOfBenefitStatus|string|null $value = null,
    ) {
    }
}
