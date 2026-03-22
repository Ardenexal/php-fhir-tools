<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\TaskIntent;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type TaskIntent
 *
 * @description Code type wrapper for TaskIntent enum
 */
class TaskIntentType extends CodePrimitive
{
    public function __construct(
        /** @param TaskIntent|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
