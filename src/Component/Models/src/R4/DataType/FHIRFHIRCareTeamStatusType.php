<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRCareTeamStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRCareTeamStatus
 *
 * @description Code type wrapper for FHIRCareTeamStatus enum
 */
class FHIRFHIRCareTeamStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRCareTeamStatus|string|null $value The code value */
        public FHIRFHIRCareTeamStatus|string|null $value = null,
    ) {
    }
}
