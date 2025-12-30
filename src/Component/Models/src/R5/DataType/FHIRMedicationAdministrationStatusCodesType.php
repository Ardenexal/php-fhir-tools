<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRMedicationAdministrationStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRMedicationAdministrationStatusCodes
 *
 * @description Code type wrapper for FHIRMedicationAdministrationStatusCodes enum
 */
class FHIRMedicationAdministrationStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @param FHIRMedicationAdministrationStatusCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
