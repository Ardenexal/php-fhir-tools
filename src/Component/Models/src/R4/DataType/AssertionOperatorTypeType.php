<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\AssertionOperatorType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type AssertionOperatorType
 *
 * @description Code type wrapper for AssertionOperatorType enum
 */
class AssertionOperatorTypeType extends CodePrimitive
{
    public function __construct(
        /** @param AssertionOperatorType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
