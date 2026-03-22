<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\AllergyIntoleranceType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type AllergyIntoleranceType
 *
 * @description Code type wrapper for AllergyIntoleranceType enum
 */
class AllergyIntoleranceTypeType extends CodePrimitive
{
    public function __construct(
        /** @param AllergyIntoleranceType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
