<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRQuestionnaireItemOperator;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRQuestionnaireItemOperator
 *
 * @description Code type wrapper for FHIRQuestionnaireItemOperator enum
 */
class FHIRQuestionnaireItemOperatorType extends FHIRCode
{
    public function __construct(
        /** @var FHIRQuestionnaireItemOperator|string|null $value The code value */
        public FHIRQuestionnaireItemOperator|string|null $value = null,
    ) {
    }
}
