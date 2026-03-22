<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\ResearchSubjectStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type ResearchSubjectStatus
 *
 * @description Code type wrapper for ResearchSubjectStatus enum
 */
class ResearchSubjectStatusType extends CodePrimitive
{
    public function __construct(
        /** @param ResearchSubjectStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
