<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\ActionCardinalityBehavior;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type ActionCardinalityBehavior
 *
 * @description Code type wrapper for ActionCardinalityBehavior enum
 */
class ActionCardinalityBehaviorType extends CodePrimitive
{
    public function __construct(
        /** @param ActionCardinalityBehavior|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
