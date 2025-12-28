<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIREligibilityResponsePurpose;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIREligibilityResponsePurpose
 *
 * @description Code type wrapper for FHIREligibilityResponsePurpose enum
 */
class FHIRFHIREligibilityResponsePurposeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIREligibilityResponsePurpose|string|null $value The code value */
        public FHIRFHIREligibilityResponsePurpose|string|null $value = null,
    ) {
    }
}
