<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRConceptMapGroupUnmappedMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConceptMapGroupUnmappedMode
 *
 * @description Code type wrapper for FHIRConceptMapGroupUnmappedMode enum
 */
class FHIRConceptMapGroupUnmappedModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRConceptMapGroupUnmappedMode|string|null $value The code value */
        public FHIRConceptMapGroupUnmappedMode|string|null $value = null,
    ) {
    }
}
