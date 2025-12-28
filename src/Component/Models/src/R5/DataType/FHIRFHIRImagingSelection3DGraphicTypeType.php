<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRImagingSelection3DGraphicType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRImagingSelection3DGraphicType
 *
 * @description Code type wrapper for FHIRImagingSelection3DGraphicType enum
 */
class FHIRFHIRImagingSelection3DGraphicTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRImagingSelection3DGraphicType|string|null $value The code value */
        public FHIRFHIRImagingSelection3DGraphicType|string|null $value = null,
    ) {
    }
}
