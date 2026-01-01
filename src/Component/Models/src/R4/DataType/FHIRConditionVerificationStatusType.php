<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

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
        /** @param FHIRConditionVerificationStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
