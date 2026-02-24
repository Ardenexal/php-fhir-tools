<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\MedicationDispenseStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type MedicationDispenseStatusCodes
 *
 * @description Code type wrapper for MedicationDispenseStatusCodes enum
 */
class MedicationDispenseStatusCodesType extends CodePrimitive
{
    public function __construct(
        /** @param MedicationDispenseStatusCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
