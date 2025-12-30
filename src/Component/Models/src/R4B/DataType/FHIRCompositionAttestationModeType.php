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
        /** @param FHIRCompositionAttestationMode|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
