<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRImagingSelection2DGraphicType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRImagingSelection2DGraphicType
 *
 * @description Code type wrapper for FHIRImagingSelection2DGraphicType enum
 */
class FHIRImagingSelection2DGraphicTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRImagingSelection2DGraphicType|string|null $value The code value */
        public FHIRImagingSelection2DGraphicType|string|null $value = null,
    ) {
    }
}
