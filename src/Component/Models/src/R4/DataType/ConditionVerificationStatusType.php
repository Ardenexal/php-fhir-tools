<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\ConditionVerificationStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type ConditionVerificationStatus
 *
 * @description Code type wrapper for ConditionVerificationStatus enum
 */
class ConditionVerificationStatusType extends CodePrimitive
{
    public function __construct(
        /** @param ConditionVerificationStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
