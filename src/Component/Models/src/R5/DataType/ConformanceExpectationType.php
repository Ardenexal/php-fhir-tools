<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\ConformanceExpectation;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type ConformanceExpectation
 *
 * @description Code type wrapper for ConformanceExpectation enum
 */
class ConformanceExpectationType extends CodePrimitive
{
    public function __construct(
        /** @param ConformanceExpectation|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
