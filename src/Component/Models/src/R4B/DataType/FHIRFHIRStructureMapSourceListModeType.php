<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRStructureMapSourceListMode;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRStructureMapSourceListMode
 *
 * @description Code type wrapper for FHIRStructureMapSourceListMode enum
 */
class FHIRFHIRStructureMapSourceListModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRStructureMapSourceListMode|string|null $value The code value */
        public FHIRFHIRStructureMapSourceListMode|string|null $value = null,
    ) {
    }
}
