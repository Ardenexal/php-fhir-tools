<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRStructureMapTransform;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRStructureMapTransform
 *
 * @description Code type wrapper for FHIRStructureMapTransform enum
 */
class FHIRStructureMapTransformType extends FHIRCode
{
    public function __construct(
        /** @var FHIRStructureMapTransform|string|null $value The code value */
        public FHIRStructureMapTransform|string|null $value = null,
    ) {
    }
}
