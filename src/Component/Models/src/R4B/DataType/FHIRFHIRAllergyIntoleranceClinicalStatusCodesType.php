<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAllergyIntoleranceClinicalStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRAllergyIntoleranceClinicalStatusCodes
 *
 * @description Code type wrapper for FHIRAllergyIntoleranceClinicalStatusCodes enum
 */
class FHIRFHIRAllergyIntoleranceClinicalStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAllergyIntoleranceClinicalStatusCodes|string|null $value The code value */
        public FHIRFHIRAllergyIntoleranceClinicalStatusCodes|string|null $value = null,
    ) {
    }
}
