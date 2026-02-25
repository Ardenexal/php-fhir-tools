<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\OperationParameterUse;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type OperationParameterUse
 *
 * @description Code type wrapper for OperationParameterUse enum
 */
class OperationParameterUseType extends CodePrimitive
{
    public function __construct(
        /** @param OperationParameterUse|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
