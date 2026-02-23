<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\RequestPriority;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type RequestPriority
 *
 * @description Code type wrapper for RequestPriority enum
 */
class RequestPriorityType extends CodePrimitive
{
    public function __construct(
        /** @param RequestPriority|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
