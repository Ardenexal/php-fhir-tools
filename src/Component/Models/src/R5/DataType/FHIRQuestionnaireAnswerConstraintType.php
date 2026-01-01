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
        /** @param FHIRQuestionnaireAnswerConstraint|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
