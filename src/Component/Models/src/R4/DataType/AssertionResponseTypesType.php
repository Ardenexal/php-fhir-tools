<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\AssertionResponseTypes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type AssertionResponseTypes
 *
 * @description Code type wrapper for AssertionResponseTypes enum
 */
class AssertionResponseTypesType extends CodePrimitive
{
    public function __construct(
        /** @param AssertionResponseTypes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
