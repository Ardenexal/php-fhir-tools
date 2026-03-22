<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\StructureMapModelMode;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type StructureMapModelMode
 *
 * @description Code type wrapper for StructureMapModelMode enum
 */
class StructureMapModelModeType extends CodePrimitive
{
    public function __construct(
        /** @param StructureMapModelMode|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
