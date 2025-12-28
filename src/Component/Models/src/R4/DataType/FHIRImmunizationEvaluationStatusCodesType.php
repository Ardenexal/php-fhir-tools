<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRImmunizationEvaluationStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRImmunizationEvaluationStatusCodes
 *
 * @description Code type wrapper for FHIRImmunizationEvaluationStatusCodes enum
 */
class FHIRImmunizationEvaluationStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRImmunizationEvaluationStatusCodes|string|null $value The code value */
        public FHIRImmunizationEvaluationStatusCodes|string|null $value = null,
    ) {
    }
}
