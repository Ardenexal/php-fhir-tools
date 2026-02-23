<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\ActionSelectionBehavior;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type ActionSelectionBehavior
 *
 * @description Code type wrapper for ActionSelectionBehavior enum
 */
class ActionSelectionBehaviorType extends CodePrimitive
{
    public function __construct(
        /** @param ActionSelectionBehavior|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
