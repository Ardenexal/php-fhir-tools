<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

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
        /** @param FHIRAllergyIntoleranceType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
