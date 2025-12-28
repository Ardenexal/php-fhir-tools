<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRMedicationKnowledgeStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRMedicationKnowledgeStatusCodes
 *
 * @description Code type wrapper for FHIRMedicationKnowledgeStatusCodes enum
 */
class FHIRMedicationKnowledgeStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRMedicationKnowledgeStatusCodes|string|null $value The code value */
        public FHIRMedicationKnowledgeStatusCodes|string|null $value = null,
    ) {
    }
}
