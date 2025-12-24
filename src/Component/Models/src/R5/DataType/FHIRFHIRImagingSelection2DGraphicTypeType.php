<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRImagingSelection2DGraphicType;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRImagingSelection2DGraphicType
 *
 * @description Code type wrapper for FHIRImagingSelection2DGraphicType enum
 */
class FHIRFHIRImagingSelection2DGraphicTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRImagingSelection2DGraphicType|string|null $value The code value */
        public FHIRFHIRImagingSelection2DGraphicType|string|null $value = null,
    ) {
    }
}
