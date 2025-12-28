<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRStructureMapModelMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRStructureMapModelMode
 *
 * @description Code type wrapper for FHIRStructureMapModelMode enum
 */
class FHIRStructureMapModelModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRStructureMapModelMode|string|null $value The code value */
        public FHIRStructureMapModelMode|string|null $value = null,
    ) {
    }
}
