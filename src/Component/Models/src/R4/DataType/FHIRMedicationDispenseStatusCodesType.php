<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRMedicationDispenseStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRMedicationDispenseStatusCodes
 *
 * @description Code type wrapper for FHIRMedicationDispenseStatusCodes enum
 */
class FHIRMedicationDispenseStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRMedicationDispenseStatusCodes|string|null $value The code value */
        public FHIRMedicationDispenseStatusCodes|string|null $value = null,
    ) {
    }
}
