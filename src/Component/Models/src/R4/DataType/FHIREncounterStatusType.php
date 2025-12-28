<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIREncounterStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIREncounterStatus
 *
 * @description Code type wrapper for FHIREncounterStatus enum
 */
class FHIREncounterStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIREncounterStatus|string|null $value The code value */
        public FHIREncounterStatus|string|null $value = null,
    ) {
    }
}
