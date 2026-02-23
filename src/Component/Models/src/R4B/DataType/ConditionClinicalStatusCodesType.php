<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\ConditionClinicalStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type ConditionClinicalStatusCodes
 *
 * @description Code type wrapper for ConditionClinicalStatusCodes enum
 */
class ConditionClinicalStatusCodesType extends CodePrimitive
{
    public function __construct(
        /** @param ConditionClinicalStatusCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
