<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\CommonLanguages;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type CommonLanguages
 *
 * @description Code type wrapper for CommonLanguages enum
 */
class CommonLanguagesType extends CodePrimitive
{
    public function __construct(
        /** @param CommonLanguages|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
