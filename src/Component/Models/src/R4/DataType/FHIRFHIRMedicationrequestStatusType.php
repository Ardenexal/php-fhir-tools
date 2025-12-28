<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRMedicationrequestStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRMedicationrequestStatus
 *
 * @description Code type wrapper for FHIRMedicationrequestStatus enum
 */
class FHIRFHIRMedicationrequestStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRMedicationrequestStatus|string|null $value The code value */
        public FHIRFHIRMedicationrequestStatus|string|null $value = null,
    ) {
    }
}
