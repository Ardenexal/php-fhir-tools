<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\AllergyIntoleranceSeverity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type AllergyIntoleranceSeverity
 *
 * @description Code type wrapper for AllergyIntoleranceSeverity enum
 */
class AllergyIntoleranceSeverityType extends CodePrimitive
{
    public function __construct(
        /** @param AllergyIntoleranceSeverity|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
