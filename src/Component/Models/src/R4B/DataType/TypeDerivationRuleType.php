<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\TypeDerivationRule;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type TypeDerivationRule
 *
 * @description Code type wrapper for TypeDerivationRule enum
 */
class TypeDerivationRuleType extends CodePrimitive
{
    public function __construct(
        /** @param TypeDerivationRule|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
