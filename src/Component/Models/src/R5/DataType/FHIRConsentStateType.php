<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRConsentState;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConsentState
 *
 * @description Code type wrapper for FHIRConsentState enum
 */
class FHIRConsentStateType extends FHIRCode
{
    public function __construct(
        /** @var FHIRConsentState|string|null $value The code value */
        public FHIRConsentState|string|null $value = null,
    ) {
    }
}
