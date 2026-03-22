<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\OperationParameterUse;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

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
