<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAllergyIntoleranceClinicalStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAllergyIntoleranceClinicalStatusCodes
 *
 * @description Code type wrapper for FHIRAllergyIntoleranceClinicalStatusCodes enum
 */
class FHIRAllergyIntoleranceClinicalStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @param FHIRAllergyIntoleranceClinicalStatusCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
