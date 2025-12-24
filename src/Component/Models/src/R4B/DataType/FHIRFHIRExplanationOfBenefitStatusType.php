<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRExplanationOfBenefitStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRExplanationOfBenefitStatus
 *
 * @description Code type wrapper for FHIRExplanationOfBenefitStatus enum
 */
class FHIRFHIRExplanationOfBenefitStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRExplanationOfBenefitStatus|string|null $value The code value */
        public FHIRFHIRExplanationOfBenefitStatus|string|null $value = null,
    ) {
    }
}
