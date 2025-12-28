<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRQuestionnaireAnswerConstraint;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRQuestionnaireAnswerConstraint
 *
 * @description Code type wrapper for FHIRQuestionnaireAnswerConstraint enum
 */
class FHIRQuestionnaireAnswerConstraintType extends FHIRCode
{
    public function __construct(
        /** @var FHIRQuestionnaireAnswerConstraint|string|null $value The code value */
        public FHIRQuestionnaireAnswerConstraint|string|null $value = null,
    ) {
    }
}
