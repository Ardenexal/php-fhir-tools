<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAllergyIntoleranceCriticality;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAllergyIntoleranceCriticality
 *
 * @description Code type wrapper for FHIRAllergyIntoleranceCriticality enum
 */
class FHIRAllergyIntoleranceCriticalityType extends FHIRCode
{
    public function __construct(
        /** @var FHIRAllergyIntoleranceCriticality|string|null $value The code value */
        public FHIRAllergyIntoleranceCriticality|string|null $value = null,
    ) {
    }
}
