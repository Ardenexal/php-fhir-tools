<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\ActionPrecheckBehavior;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type ActionPrecheckBehavior
 *
 * @description Code type wrapper for ActionPrecheckBehavior enum
 */
class ActionPrecheckBehaviorType extends CodePrimitive
{
    public function __construct(
        /** @param ActionPrecheckBehavior|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
