<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\StructureDefinitionKind;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type StructureDefinitionKind
 *
 * @description Code type wrapper for StructureDefinitionKind enum
 */
class StructureDefinitionKindType extends CodePrimitive
{
    public function __construct(
        /** @param StructureDefinitionKind|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
