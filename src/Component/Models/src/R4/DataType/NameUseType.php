<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\NameUse;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type NameUse
 *
 * @description Code type wrapper for NameUse enum
 */
class NameUseType extends CodePrimitive
{
    public function __construct(
        /** @param NameUse|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
