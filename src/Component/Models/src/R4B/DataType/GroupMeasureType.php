<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\GroupMeasure;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type GroupMeasure
 *
 * @description Code type wrapper for GroupMeasure enum
 */
class GroupMeasureType extends CodePrimitive
{
    public function __construct(
        /** @param GroupMeasure|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
