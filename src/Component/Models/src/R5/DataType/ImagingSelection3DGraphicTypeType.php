<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\ImagingSelection3DGraphicType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type ImagingSelection3DGraphicType
 *
 * @description Code type wrapper for ImagingSelection3DGraphicType enum
 */
class ImagingSelection3DGraphicTypeType extends CodePrimitive
{
    public function __construct(
        /** @param ImagingSelection3DGraphicType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
