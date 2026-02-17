<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\ConditionalDeleteStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type ConditionalDeleteStatus
 *
 * @description Code type wrapper for ConditionalDeleteStatus enum
 */
class ConditionalDeleteStatusType extends CodePrimitive
{
    public function __construct(
        /** @param ConditionalDeleteStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
