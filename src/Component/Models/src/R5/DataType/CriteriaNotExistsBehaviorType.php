<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\CriteriaNotExistsBehavior;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type CriteriaNotExistsBehavior
 *
 * @description Code type wrapper for CriteriaNotExistsBehavior enum
 */
class CriteriaNotExistsBehaviorType extends CodePrimitive
{
    public function __construct(
        /** @param CriteriaNotExistsBehavior|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
