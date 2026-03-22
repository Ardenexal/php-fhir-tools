<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\MedicationrequestStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type MedicationrequestStatus
 *
 * @description Code type wrapper for MedicationrequestStatus enum
 */
class MedicationrequestStatusType extends CodePrimitive
{
    public function __construct(
        /** @param MedicationrequestStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
