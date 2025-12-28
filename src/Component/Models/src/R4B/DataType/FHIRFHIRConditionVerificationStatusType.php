<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRConditionVerificationStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConditionVerificationStatus
 *
 * @description Code type wrapper for FHIRConditionVerificationStatus enum
 */
class FHIRFHIRConditionVerificationStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRConditionVerificationStatus|string|null $value The code value */
        public FHIRFHIRConditionVerificationStatus|string|null $value = null,
    ) {
    }
}
