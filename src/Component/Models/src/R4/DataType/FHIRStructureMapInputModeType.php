<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRStructureMapInputMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRStructureMapInputMode
 *
 * @description Code type wrapper for FHIRStructureMapInputMode enum
 */
class FHIRStructureMapInputModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRStructureMapInputMode|string|null $value The code value */
        public FHIRStructureMapInputMode|string|null $value = null,
    ) {
    }
}
