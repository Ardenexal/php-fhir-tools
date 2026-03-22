<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\AllergyIntoleranceCriticality;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type AllergyIntoleranceCriticality
 *
 * @description Code type wrapper for AllergyIntoleranceCriticality enum
 */
class AllergyIntoleranceCriticalityType extends CodePrimitive
{
    public function __construct(
        /** @param AllergyIntoleranceCriticality|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
