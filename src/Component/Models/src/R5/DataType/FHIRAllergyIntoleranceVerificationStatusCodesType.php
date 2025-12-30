<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAllergyIntoleranceVerificationStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAllergyIntoleranceVerificationStatusCodes
 *
 * @description Code type wrapper for FHIRAllergyIntoleranceVerificationStatusCodes enum
 */
class FHIRAllergyIntoleranceVerificationStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @param FHIRAllergyIntoleranceVerificationStatusCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
