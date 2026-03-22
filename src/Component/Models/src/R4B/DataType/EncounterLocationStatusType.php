<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\EncounterLocationStatus;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type EncounterLocationStatus
 *
 * @description Code type wrapper for EncounterLocationStatus enum
 */
class EncounterLocationStatusType extends CodePrimitive
{
    public function __construct(
        /** @param EncounterLocationStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
