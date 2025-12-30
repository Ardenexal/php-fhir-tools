<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

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
        /** @param FHIRMedicationStatusCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
