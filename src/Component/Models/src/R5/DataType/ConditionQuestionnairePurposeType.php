<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\ConditionQuestionnairePurpose;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type ConditionQuestionnairePurpose
 *
 * @description Code type wrapper for ConditionQuestionnairePurpose enum
 */
class ConditionQuestionnairePurposeType extends CodePrimitive
{
    public function __construct(
        /** @param ConditionQuestionnairePurpose|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
