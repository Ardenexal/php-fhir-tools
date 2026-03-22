<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\MedicationKnowledgeStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type MedicationKnowledgeStatusCodes
 *
 * @description Code type wrapper for MedicationKnowledgeStatusCodes enum
 */
class MedicationKnowledgeStatusCodesType extends CodePrimitive
{
    public function __construct(
        /** @param MedicationKnowledgeStatusCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
