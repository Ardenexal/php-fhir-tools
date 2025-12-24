<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRConceptMapGroupUnmappedMode;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRConceptMapGroupUnmappedMode
 *
 * @description Code type wrapper for FHIRConceptMapGroupUnmappedMode enum
 */
class FHIRFHIRConceptMapGroupUnmappedModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRConceptMapGroupUnmappedMode|string|null $value The code value */
        public FHIRFHIRConceptMapGroupUnmappedMode|string|null $value = null,
    ) {
    }
}
