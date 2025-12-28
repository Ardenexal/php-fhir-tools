<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAllergyIntoleranceType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAllergyIntoleranceType
 *
 * @description Code type wrapper for FHIRAllergyIntoleranceType enum
 */
class FHIRAllergyIntoleranceTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRAllergyIntoleranceType|string|null $value The code value */
        public FHIRAllergyIntoleranceType|string|null $value = null,
    ) {
    }
}
