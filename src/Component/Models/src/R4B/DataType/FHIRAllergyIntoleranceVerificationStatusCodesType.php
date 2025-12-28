<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

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
        /** @var FHIRAllergyIntoleranceVerificationStatusCodes|string|null $value The code value */
        public FHIRAllergyIntoleranceVerificationStatusCodes|string|null $value = null,
    ) {
    }
}
