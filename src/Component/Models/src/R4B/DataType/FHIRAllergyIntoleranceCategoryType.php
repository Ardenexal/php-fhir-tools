<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAllergyIntoleranceCategory;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAllergyIntoleranceCategory
 *
 * @description Code type wrapper for FHIRAllergyIntoleranceCategory enum
 */
class FHIRAllergyIntoleranceCategoryType extends FHIRCode
{
    public function __construct(
        /** @var FHIRAllergyIntoleranceCategory|string|null $value The code value */
        public FHIRAllergyIntoleranceCategory|string|null $value = null,
    ) {
    }
}
