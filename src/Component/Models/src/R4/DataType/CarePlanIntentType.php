<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\CarePlanIntent;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type CarePlanIntent
 *
 * @description Code type wrapper for CarePlanIntent enum
 */
class CarePlanIntentType extends CodePrimitive
{
    public function __construct(
        /** @param CarePlanIntent|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
