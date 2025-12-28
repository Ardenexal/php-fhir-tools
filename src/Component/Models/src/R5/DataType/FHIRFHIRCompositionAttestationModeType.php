<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRCompositionAttestationMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCompositionAttestationMode
 *
 * @description Code type wrapper for FHIRCompositionAttestationMode enum
 */
class FHIRFHIRCompositionAttestationModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRCompositionAttestationMode|string|null $value The code value */
        public FHIRFHIRCompositionAttestationMode|string|null $value = null,
    ) {
    }
}
