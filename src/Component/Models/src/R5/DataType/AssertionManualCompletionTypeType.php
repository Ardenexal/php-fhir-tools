<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\AssertionManualCompletionType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type AssertionManualCompletionType
 *
 * @description Code type wrapper for AssertionManualCompletionType enum
 */
class AssertionManualCompletionTypeType extends CodePrimitive
{
    public function __construct(
        /** @param AssertionManualCompletionType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
