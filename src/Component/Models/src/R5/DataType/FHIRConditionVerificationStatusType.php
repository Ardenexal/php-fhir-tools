<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRConditionVerificationStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConditionVerificationStatus
 *
 * @description Code type wrapper for FHIRConditionVerificationStatus enum
 */
class FHIRConditionVerificationStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRConditionVerificationStatus|string|null $value The code value */
        public FHIRConditionVerificationStatus|string|null $value = null,
    ) {
    }
}
