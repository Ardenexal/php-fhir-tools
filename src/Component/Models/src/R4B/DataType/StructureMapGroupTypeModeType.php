<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\StructureMapGroupTypeMode;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type StructureMapGroupTypeMode
 *
 * @description Code type wrapper for StructureMapGroupTypeMode enum
 */
class StructureMapGroupTypeModeType extends CodePrimitive
{
    public function __construct(
        /** @param StructureMapGroupTypeMode|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
