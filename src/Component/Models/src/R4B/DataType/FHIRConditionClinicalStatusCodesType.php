<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRConditionClinicalStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConditionClinicalStatusCodes
 *
 * @description Code type wrapper for FHIRConditionClinicalStatusCodes enum
 */
class FHIRConditionClinicalStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @param FHIRConditionClinicalStatusCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
