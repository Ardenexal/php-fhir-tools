<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAllergyIntoleranceSeverity;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRAllergyIntoleranceSeverity
 *
 * @description Code type wrapper for FHIRAllergyIntoleranceSeverity enum
 */
class FHIRFHIRAllergyIntoleranceSeverityType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAllergyIntoleranceSeverity|string|null $value The code value */
        public FHIRFHIRAllergyIntoleranceSeverity|string|null $value = null,
    ) {
    }
}
