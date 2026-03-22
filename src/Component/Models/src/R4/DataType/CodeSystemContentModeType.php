<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\CodeSystemContentMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type CodeSystemContentMode
 *
 * @description Code type wrapper for CodeSystemContentMode enum
 */
class CodeSystemContentModeType extends CodePrimitive
{
    public function __construct(
        /** @param CodeSystemContentMode|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
