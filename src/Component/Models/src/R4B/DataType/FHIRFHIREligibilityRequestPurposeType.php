<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIREligibilityRequestPurpose;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIREligibilityRequestPurpose
 *
 * @description Code type wrapper for FHIREligibilityRequestPurpose enum
 */
class FHIRFHIREligibilityRequestPurposeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIREligibilityRequestPurpose|string|null $value The code value */
        public FHIRFHIREligibilityRequestPurpose|string|null $value = null,
    ) {
    }
}
