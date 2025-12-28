<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRAppointmentResponseStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAppointmentResponseStatus
 *
 * @description Code type wrapper for FHIRAppointmentResponseStatus enum
 */
class FHIRAppointmentResponseStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRAppointmentResponseStatus|string|null $value The code value */
        public FHIRAppointmentResponseStatus|string|null $value = null,
    ) {
    }
}
