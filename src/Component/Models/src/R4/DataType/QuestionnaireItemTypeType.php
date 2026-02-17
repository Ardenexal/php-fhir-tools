<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\QuestionnaireItemType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type QuestionnaireItemType
 *
 * @description Code type wrapper for QuestionnaireItemType enum
 */
class QuestionnaireItemTypeType extends CodePrimitive
{
    public function __construct(
        /** @param QuestionnaireItemType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
