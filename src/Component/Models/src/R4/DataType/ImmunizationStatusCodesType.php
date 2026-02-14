<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\ImmunizationStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type ImmunizationStatusCodes
 *
 * @description Code type wrapper for ImmunizationStatusCodes enum
 */
class ImmunizationStatusCodesType extends CodePrimitive
{
    public function __construct(
        /** @param ImmunizationStatusCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
