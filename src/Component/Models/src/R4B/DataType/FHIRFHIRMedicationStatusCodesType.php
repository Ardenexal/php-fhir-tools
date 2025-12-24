<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRMedicationStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRMedicationStatusCodes
 *
 * @description Code type wrapper for FHIRMedicationStatusCodes enum
 */
class FHIRFHIRMedicationStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRMedicationStatusCodes|string|null $value The code value */
        public FHIRFHIRMedicationStatusCodes|string|null $value = null,
    ) {
    }
}
