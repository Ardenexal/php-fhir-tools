<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

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
        /** @var FHIREncounterLocationStatus|string|null $value The code value */
        public FHIREncounterLocationStatus|string|null $value = null,
    ) {
    }
}
