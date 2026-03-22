<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\ResearchElementType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type ResearchElementType
 *
 * @description Code type wrapper for ResearchElementType enum
 */
class ResearchElementTypeType extends CodePrimitive
{
    public function __construct(
        /** @param ResearchElementType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
