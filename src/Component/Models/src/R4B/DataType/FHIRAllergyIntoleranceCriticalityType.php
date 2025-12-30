<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

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
        /** @param FHIRAllergyIntoleranceCriticality|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
