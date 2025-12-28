<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRMedicationDispenseStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRMedicationDispenseStatusCodes
 *
 * @description Code type wrapper for FHIRMedicationDispenseStatusCodes enum
 */
class FHIRFHIRMedicationDispenseStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRMedicationDispenseStatusCodes|string|null $value The code value */
        public FHIRFHIRMedicationDispenseStatusCodes|string|null $value = null,
    ) {
    }
}
