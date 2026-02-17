<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\CodeSearchSupport;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type CodeSearchSupport
 *
 * @description Code type wrapper for CodeSearchSupport enum
 */
class CodeSearchSupportType extends CodePrimitive
{
    public function __construct(
        /** @param CodeSearchSupport|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
