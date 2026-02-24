<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\RequestIntent;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type RequestIntent
 *
 * @description Code type wrapper for RequestIntent enum
 */
class RequestIntentType extends CodePrimitive
{
    public function __construct(
        /** @param RequestIntent|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
