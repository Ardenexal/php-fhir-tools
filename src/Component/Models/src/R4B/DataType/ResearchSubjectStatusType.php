<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\ResearchSubjectStatus;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

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
