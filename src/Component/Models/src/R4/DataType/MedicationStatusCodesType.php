<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\MedicationStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type MedicationStatusCodes
 *
 * @description Code type wrapper for MedicationStatusCodes enum
 */
class MedicationStatusCodesType extends CodePrimitive
{
    public function __construct(
        /** @param MedicationStatusCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
