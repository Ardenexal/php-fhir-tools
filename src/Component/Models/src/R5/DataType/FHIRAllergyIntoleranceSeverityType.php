<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAllergyIntoleranceSeverity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAllergyIntoleranceSeverity
 *
 * @description Code type wrapper for FHIRAllergyIntoleranceSeverity enum
 */
class FHIRAllergyIntoleranceSeverityType extends FHIRCode
{
    public function __construct(
        /** @var FHIRAllergyIntoleranceSeverity|string|null $value The code value */
        public FHIRAllergyIntoleranceSeverity|string|null $value = null,
    ) {
    }
}
