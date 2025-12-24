<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAllergyIntoleranceCategory;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRAllergyIntoleranceCategory
 *
 * @description Code type wrapper for FHIRAllergyIntoleranceCategory enum
 */
class FHIRFHIRAllergyIntoleranceCategoryType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAllergyIntoleranceCategory|string|null $value The code value */
        public FHIRFHIRAllergyIntoleranceCategory|string|null $value = null,
    ) {
    }
}
