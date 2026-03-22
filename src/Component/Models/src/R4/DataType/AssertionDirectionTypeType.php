<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\AssertionDirectionType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type AssertionDirectionType
 *
 * @description Code type wrapper for AssertionDirectionType enum
 */
class AssertionDirectionTypeType extends CodePrimitive
{
    public function __construct(
        /** @param AssertionDirectionType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
