<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRStructureMapModelMode;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRStructureMapModelMode
 *
 * @description Code type wrapper for FHIRStructureMapModelMode enum
 */
class FHIRFHIRStructureMapModelModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRStructureMapModelMode|string|null $value The code value */
        public FHIRFHIRStructureMapModelMode|string|null $value = null,
    ) {
    }
}
