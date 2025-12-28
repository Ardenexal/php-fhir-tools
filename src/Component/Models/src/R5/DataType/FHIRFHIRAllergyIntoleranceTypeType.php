<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAllergyIntoleranceType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAllergyIntoleranceType
 *
 * @description Code type wrapper for FHIRAllergyIntoleranceType enum
 */
class FHIRFHIRAllergyIntoleranceTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAllergyIntoleranceType|string|null $value The code value */
        public FHIRFHIRAllergyIntoleranceType|string|null $value = null,
    ) {
    }
}
