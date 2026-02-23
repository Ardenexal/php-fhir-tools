<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\MedicationAdministrationStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type MedicationAdministrationStatusCodes
 *
 * @description Code type wrapper for MedicationAdministrationStatusCodes enum
 */
class MedicationAdministrationStatusCodesType extends CodePrimitive
{
    public function __construct(
        /** @param MedicationAdministrationStatusCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
