<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\ActionGroupingBehavior;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type ActionGroupingBehavior
 *
 * @description Code type wrapper for ActionGroupingBehavior enum
 */
class ActionGroupingBehaviorType extends CodePrimitive
{
    public function __construct(
        /** @param ActionGroupingBehavior|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
