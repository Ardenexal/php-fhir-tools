<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRMedicationStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRMedicationStatusCodes
 *
 * @description Code type wrapper for FHIRMedicationStatusCodes enum
 */
class FHIRMedicationStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRMedicationStatusCodes|string|null $value The code value */
        public FHIRMedicationStatusCodes|string|null $value = null,
    ) {
    }
}
