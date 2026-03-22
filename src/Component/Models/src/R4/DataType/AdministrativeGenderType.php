<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\AdministrativeGender;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type AdministrativeGender
 *
 * @description Code type wrapper for AdministrativeGender enum
 */
class AdministrativeGenderType extends CodePrimitive
{
    public function __construct(
        /** @param AdministrativeGender|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
