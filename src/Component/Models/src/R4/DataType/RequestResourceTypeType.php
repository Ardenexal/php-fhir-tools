<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\RequestResourceType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type RequestResourceType
 *
 * @description Code type wrapper for RequestResourceType enum
 */
class RequestResourceTypeType extends CodePrimitive
{
    public function __construct(
        /** @param RequestResourceType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
