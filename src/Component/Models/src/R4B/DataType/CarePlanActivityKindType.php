<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\CarePlanActivityKind;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type CarePlanActivityKind
 *
 * @description Code type wrapper for CarePlanActivityKind enum
 */
class CarePlanActivityKindType extends CodePrimitive
{
    public function __construct(
        /** @param CarePlanActivityKind|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
