<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\AdverseEventStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type AdverseEventStatus
 *
 * @description Code type wrapper for AdverseEventStatus enum
 */
class AdverseEventStatusType extends CodePrimitive
{
    public function __construct(
        /** @param AdverseEventStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
