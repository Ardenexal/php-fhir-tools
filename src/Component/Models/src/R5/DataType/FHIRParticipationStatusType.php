<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRParticipationStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRParticipationStatus
 *
 * @description Code type wrapper for FHIRParticipationStatus enum
 */
class FHIRParticipationStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRParticipationStatus|string|null $value The code value */
        public FHIRParticipationStatus|string|null $value = null,
    ) {
    }
}
