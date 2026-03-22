<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\ActionRequiredBehavior;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type ActionRequiredBehavior
 *
 * @description Code type wrapper for ActionRequiredBehavior enum
 */
class ActionRequiredBehaviorType extends CodePrimitive
{
    public function __construct(
        /** @param ActionRequiredBehavior|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
