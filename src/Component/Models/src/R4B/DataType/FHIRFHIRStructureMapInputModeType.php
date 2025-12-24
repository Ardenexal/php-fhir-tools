<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRStructureMapInputMode;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRStructureMapInputMode
 *
 * @description Code type wrapper for FHIRStructureMapInputMode enum
 */
class FHIRFHIRStructureMapInputModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRStructureMapInputMode|string|null $value The code value */
        public FHIRFHIRStructureMapInputMode|string|null $value = null,
    ) {
    }
}
