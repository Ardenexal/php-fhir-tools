<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\ImagingSelection2DGraphicType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type ImagingSelection2DGraphicType
 *
 * @description Code type wrapper for ImagingSelection2DGraphicType enum
 */
class ImagingSelection2DGraphicTypeType extends CodePrimitive
{
    public function __construct(
        /** @param ImagingSelection2DGraphicType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
