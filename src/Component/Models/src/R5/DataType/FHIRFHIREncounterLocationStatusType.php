<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIREncounterLocationStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIREncounterLocationStatus
 *
 * @description Code type wrapper for FHIREncounterLocationStatus enum
 */
class FHIRFHIREncounterLocationStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIREncounterLocationStatus|string|null $value The code value */
        public FHIRFHIREncounterLocationStatus|string|null $value = null,
    ) {
    }
}
