<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRCompositionAttestationMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCompositionAttestationMode
 *
 * @description Code type wrapper for FHIRCompositionAttestationMode enum
 */
class FHIRCompositionAttestationModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRCompositionAttestationMode|string|null $value The code value */
        public FHIRCompositionAttestationMode|string|null $value = null,
    ) {
    }
}
