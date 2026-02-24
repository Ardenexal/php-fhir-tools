<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\CarePlanActivityStatus;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type CarePlanActivityStatus
 *
 * @description Code type wrapper for CarePlanActivityStatus enum
 */
class CarePlanActivityStatusType extends CodePrimitive
{
    public function __construct(
        /** @param CarePlanActivityStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
