<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\QuestionnaireItemDisabledDisplay;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type QuestionnaireItemDisabledDisplay
 *
 * @description Code type wrapper for QuestionnaireItemDisabledDisplay enum
 */
class QuestionnaireItemDisabledDisplayType extends CodePrimitive
{
    public function __construct(
        /** @param QuestionnaireItemDisabledDisplay|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
