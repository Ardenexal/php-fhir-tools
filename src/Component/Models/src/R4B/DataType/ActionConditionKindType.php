<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\ActionConditionKind;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type ActionConditionKind
 *
 * @description Code type wrapper for ActionConditionKind enum
 */
class ActionConditionKindType extends CodePrimitive
{
    public function __construct(
        /** @param ActionConditionKind|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
