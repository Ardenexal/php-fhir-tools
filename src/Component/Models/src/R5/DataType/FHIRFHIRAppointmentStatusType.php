<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAppointmentStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRAppointmentStatus
 *
 * @description Code type wrapper for FHIRAppointmentStatus enum
 */
class FHIRFHIRAppointmentStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAppointmentStatus|string|null $value The code value */
        public FHIRFHIRAppointmentStatus|string|null $value = null,
    ) {
    }
}
