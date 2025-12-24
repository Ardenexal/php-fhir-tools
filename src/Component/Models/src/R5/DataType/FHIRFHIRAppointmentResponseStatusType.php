<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRAppointmentResponseStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRAppointmentResponseStatus
 *
 * @description Code type wrapper for FHIRAppointmentResponseStatus enum
 */
class FHIRFHIRAppointmentResponseStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAppointmentResponseStatus|string|null $value The code value */
        public FHIRFHIRAppointmentResponseStatus|string|null $value = null,
    ) {
    }
}
