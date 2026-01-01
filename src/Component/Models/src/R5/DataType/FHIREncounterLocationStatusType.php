<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIREncounterLocationStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIREncounterLocationStatus
 *
 * @description Code type wrapper for FHIREncounterLocationStatus enum
 */
class FHIREncounterLocationStatusType extends FHIRCode
{
    public function __construct(
        /** @param FHIREncounterLocationStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
