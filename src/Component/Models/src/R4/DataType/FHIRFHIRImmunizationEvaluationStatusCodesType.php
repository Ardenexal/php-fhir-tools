<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRImmunizationEvaluationStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRImmunizationEvaluationStatusCodes
 *
 * @description Code type wrapper for FHIRImmunizationEvaluationStatusCodes enum
 */
class FHIRFHIRImmunizationEvaluationStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRImmunizationEvaluationStatusCodes|string|null $value The code value */
        public FHIRFHIRImmunizationEvaluationStatusCodes|string|null $value = null,
    ) {
    }
}
