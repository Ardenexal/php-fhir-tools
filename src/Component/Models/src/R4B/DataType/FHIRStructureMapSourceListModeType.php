<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRStructureMapSourceListMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRStructureMapSourceListMode
 *
 * @description Code type wrapper for FHIRStructureMapSourceListMode enum
 */
class FHIRStructureMapSourceListModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRStructureMapSourceListMode|string|null $value The code value */
        public FHIRStructureMapSourceListMode|string|null $value = null,
    ) {
    }
}
