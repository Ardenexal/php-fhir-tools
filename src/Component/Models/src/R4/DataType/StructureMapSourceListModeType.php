<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\StructureMapSourceListMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type StructureMapSourceListMode
 *
 * @description Code type wrapper for StructureMapSourceListMode enum
 */
class StructureMapSourceListModeType extends CodePrimitive
{
    public function __construct(
        /** @param StructureMapSourceListMode|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
