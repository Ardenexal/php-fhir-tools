<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\AllergyIntoleranceClinicalStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type AllergyIntoleranceClinicalStatusCodes
 *
 * @description Code type wrapper for AllergyIntoleranceClinicalStatusCodes enum
 */
class AllergyIntoleranceClinicalStatusCodesType extends CodePrimitive
{
    public function __construct(
        /** @param AllergyIntoleranceClinicalStatusCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
