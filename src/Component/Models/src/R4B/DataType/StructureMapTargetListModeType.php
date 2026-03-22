<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\StructureMapTargetListMode;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type StructureMapTargetListMode
 *
 * @description Code type wrapper for StructureMapTargetListMode enum
 */
class StructureMapTargetListModeType extends CodePrimitive
{
    public function __construct(
        /** @param StructureMapTargetListMode|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
