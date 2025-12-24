<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRStructureMapTransform;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRStructureMapTransform
 *
 * @description Code type wrapper for FHIRStructureMapTransform enum
 */
class FHIRFHIRStructureMapTransformType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRStructureMapTransform|string|null $value The code value */
        public FHIRFHIRStructureMapTransform|string|null $value = null,
    ) {
    }
}
