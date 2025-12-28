<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRStructureMapGroupTypeMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRStructureMapGroupTypeMode
 *
 * @description Code type wrapper for FHIRStructureMapGroupTypeMode enum
 */
class FHIRStructureMapGroupTypeModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRStructureMapGroupTypeMode|string|null $value The code value */
        public FHIRStructureMapGroupTypeMode|string|null $value = null,
    ) {
    }
}
