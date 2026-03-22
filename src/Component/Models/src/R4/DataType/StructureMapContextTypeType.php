<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\StructureMapContextType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type StructureMapContextType
 *
 * @description Code type wrapper for StructureMapContextType enum
 */
class StructureMapContextTypeType extends CodePrimitive
{
    public function __construct(
        /** @param StructureMapContextType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
