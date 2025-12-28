<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRQuestionnaireItemOperator;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRQuestionnaireItemOperator
 *
 * @description Code type wrapper for FHIRQuestionnaireItemOperator enum
 */
class FHIRFHIRQuestionnaireItemOperatorType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRQuestionnaireItemOperator|string|null $value The code value */
        public FHIRFHIRQuestionnaireItemOperator|string|null $value = null,
    ) {
    }
}
