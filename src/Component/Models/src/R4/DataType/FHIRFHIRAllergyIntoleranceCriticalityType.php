<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAllergyIntoleranceCriticality;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRAllergyIntoleranceCriticality
 *
 * @description Code type wrapper for FHIRAllergyIntoleranceCriticality enum
 */
class FHIRFHIRAllergyIntoleranceCriticalityType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAllergyIntoleranceCriticality|string|null $value The code value */
        public FHIRFHIRAllergyIntoleranceCriticality|string|null $value = null,
    ) {
    }
}
