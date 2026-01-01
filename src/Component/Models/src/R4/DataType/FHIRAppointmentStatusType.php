<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

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
        /** @param FHIRAppointmentStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
