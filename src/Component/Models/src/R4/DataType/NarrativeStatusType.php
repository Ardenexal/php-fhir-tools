<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\NarrativeStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type NarrativeStatus
 *
 * @description Code type wrapper for NarrativeStatus enum
 */
class NarrativeStatusType extends CodePrimitive
{
    public function __construct(
        /** @param NarrativeStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
