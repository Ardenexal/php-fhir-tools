<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\HTTPVerb;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type HTTPVerb
 *
 * @description Code type wrapper for HTTPVerb enum
 */
class HTTPVerbType extends CodePrimitive
{
    public function __construct(
        /** @param HTTPVerb|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
