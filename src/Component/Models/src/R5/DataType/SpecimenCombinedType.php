<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\SpecimenCombined;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type SpecimenCombined
 *
 * @description Code type wrapper for SpecimenCombined enum
 */
class SpecimenCombinedType extends CodePrimitive
{
    public function __construct(
        /** @param SpecimenCombined|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
