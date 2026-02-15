<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\IdentifierUse;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type IdentifierUse
 *
 * @description Code type wrapper for IdentifierUse enum
 */
class IdentifierUseType extends CodePrimitive
{
    public function __construct(
        /** @param IdentifierUse|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
