<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\VerificationResultStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type VerificationResultStatus
 *
 * @description Code type wrapper for VerificationResultStatus enum
 */
class VerificationResultStatusType extends CodePrimitive
{
    public function __construct(
        /** @param VerificationResultStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
