<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\AllLanguages;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type AllLanguages
 *
 * @description Code type wrapper for AllLanguages enum
 */
class AllLanguagesType extends CodePrimitive
{
    public function __construct(
        /** @param AllLanguages|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
