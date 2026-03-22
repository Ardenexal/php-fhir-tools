<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\AllergyIntoleranceVerificationStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type AllergyIntoleranceVerificationStatusCodes
 *
 * @description Code type wrapper for AllergyIntoleranceVerificationStatusCodes enum
 */
class AllergyIntoleranceVerificationStatusCodesType extends CodePrimitive
{
    public function __construct(
        /** @param AllergyIntoleranceVerificationStatusCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
