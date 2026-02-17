<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\StructureMapModelMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

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
