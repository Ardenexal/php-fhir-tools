<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\StructureMapTransform;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type StructureMapTransform
 *
 * @description Code type wrapper for StructureMapTransform enum
 */
class StructureMapTransformType extends CodePrimitive
{
    public function __construct(
        /** @param StructureMapTransform|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
