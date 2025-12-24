<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRMedicationKnowledgeStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRMedicationKnowledgeStatusCodes
 *
 * @description Code type wrapper for FHIRMedicationKnowledgeStatusCodes enum
 */
class FHIRFHIRMedicationKnowledgeStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRMedicationKnowledgeStatusCodes|string|null $value The code value */
        public FHIRFHIRMedicationKnowledgeStatusCodes|string|null $value = null,
    ) {
    }
}
