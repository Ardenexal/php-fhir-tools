<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRConsentState;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConsentState
 *
 * @description Code type wrapper for FHIRConsentState enum
 */
class FHIRFHIRConsentStateType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRConsentState|string|null $value The code value */
        public FHIRFHIRConsentState|string|null $value = null,
    ) {
    }
}
