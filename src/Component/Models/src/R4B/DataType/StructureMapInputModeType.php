<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\StructureMapInputMode;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type StructureMapInputMode
 *
 * @description Code type wrapper for StructureMapInputMode enum
 */
class StructureMapInputModeType extends CodePrimitive
{
    public function __construct(
        /** @param StructureMapInputMode|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
