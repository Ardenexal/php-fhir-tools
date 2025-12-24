<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIREncounterStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIREncounterStatus
 *
 * @description Code type wrapper for FHIREncounterStatus enum
 */
class FHIRFHIREncounterStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIREncounterStatus|string|null $value The code value */
        public FHIRFHIREncounterStatus|string|null $value = null,
    ) {
    }
}
