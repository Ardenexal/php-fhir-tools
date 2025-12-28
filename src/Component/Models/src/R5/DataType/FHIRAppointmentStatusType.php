<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAppointmentStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAppointmentStatus
 *
 * @description Code type wrapper for FHIRAppointmentStatus enum
 */
class FHIRAppointmentStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRAppointmentStatus|string|null $value The code value */
        public FHIRAppointmentStatus|string|null $value = null,
    ) {
    }
}
