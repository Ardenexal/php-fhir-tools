<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRMedicationrequestStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRMedicationrequestStatus
 *
 * @description Code type wrapper for FHIRMedicationrequestStatus enum
 */
class FHIRMedicationrequestStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRMedicationrequestStatus|string|null $value The code value */
        public FHIRMedicationrequestStatus|string|null $value = null,
    ) {
    }
}
