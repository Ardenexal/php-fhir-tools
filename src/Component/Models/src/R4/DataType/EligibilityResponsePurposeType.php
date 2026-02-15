<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\EligibilityResponsePurpose;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type EligibilityResponsePurpose
 *
 * @description Code type wrapper for EligibilityResponsePurpose enum
 */
class EligibilityResponsePurposeType extends CodePrimitive
{
    public function __construct(
        /** @param EligibilityResponsePurpose|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
