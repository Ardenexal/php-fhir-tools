<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\ConditionPreconditionType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type ConditionPreconditionType
 *
 * @description Code type wrapper for ConditionPreconditionType enum
 */
class ConditionPreconditionTypeType extends CodePrimitive
{
    public function __construct(
        /** @param ConditionPreconditionType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
