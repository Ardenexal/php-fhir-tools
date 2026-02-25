<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\QuestionnaireAnswerConstraint;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type QuestionnaireAnswerConstraint
 *
 * @description Code type wrapper for QuestionnaireAnswerConstraint enum
 */
class QuestionnaireAnswerConstraintType extends CodePrimitive
{
    public function __construct(
        /** @param QuestionnaireAnswerConstraint|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
