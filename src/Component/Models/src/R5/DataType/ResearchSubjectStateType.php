<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\ResearchSubjectState;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type ResearchSubjectState
 *
 * @description Code type wrapper for ResearchSubjectState enum
 */
class ResearchSubjectStateType extends CodePrimitive
{
    public function __construct(
        /** @param ResearchSubjectState|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
