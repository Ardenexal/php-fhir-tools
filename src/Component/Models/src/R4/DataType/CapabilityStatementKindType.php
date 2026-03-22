<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\CapabilityStatementKind;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type CapabilityStatementKind
 *
 * @description Code type wrapper for CapabilityStatementKind enum
 */
class CapabilityStatementKindType extends CodePrimitive
{
    public function __construct(
        /** @param CapabilityStatementKind|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
