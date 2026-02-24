<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\SpecimenContainedPreference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type SpecimenContainedPreference
 *
 * @description Code type wrapper for SpecimenContainedPreference enum
 */
class SpecimenContainedPreferenceType extends CodePrimitive
{
    public function __construct(
        /** @param SpecimenContainedPreference|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
