<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRParticipationStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRParticipationStatus
 *
 * @description Code type wrapper for FHIRParticipationStatus enum
 */
class FHIRFHIRParticipationStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRParticipationStatus|string|null $value The code value */
        public FHIRFHIRParticipationStatus|string|null $value = null,
    ) {
    }
}
