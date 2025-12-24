<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRStructureMapGroupTypeMode;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRStructureMapGroupTypeMode
 *
 * @description Code type wrapper for FHIRStructureMapGroupTypeMode enum
 */
class FHIRFHIRStructureMapGroupTypeModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRStructureMapGroupTypeMode|string|null $value The code value */
        public FHIRFHIRStructureMapGroupTypeMode|string|null $value = null,
    ) {
    }
}
