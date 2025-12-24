<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAllergyIntoleranceVerificationStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRAllergyIntoleranceVerificationStatusCodes
 *
 * @description Code type wrapper for FHIRAllergyIntoleranceVerificationStatusCodes enum
 */
class FHIRFHIRAllergyIntoleranceVerificationStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAllergyIntoleranceVerificationStatusCodes|string|null $value The code value */
        public FHIRFHIRAllergyIntoleranceVerificationStatusCodes|string|null $value = null,
    ) {
    }
}
