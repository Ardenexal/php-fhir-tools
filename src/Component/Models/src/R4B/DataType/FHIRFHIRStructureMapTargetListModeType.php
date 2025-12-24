<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRStructureMapTargetListMode;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRStructureMapTargetListMode
 *
 * @description Code type wrapper for FHIRStructureMapTargetListMode enum
 */
class FHIRFHIRStructureMapTargetListModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRStructureMapTargetListMode|string|null $value The code value */
        public FHIRFHIRStructureMapTargetListMode|string|null $value = null,
    ) {
    }
}
