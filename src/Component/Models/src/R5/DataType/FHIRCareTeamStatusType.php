<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRCareTeamStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCareTeamStatus
 *
 * @description Code type wrapper for FHIRCareTeamStatus enum
 */
class FHIRCareTeamStatusType extends FHIRCode
{
    public function __construct(
        /** @param FHIRCareTeamStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
