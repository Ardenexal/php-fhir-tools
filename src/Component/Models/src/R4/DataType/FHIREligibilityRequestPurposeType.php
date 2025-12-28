<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIREligibilityRequestPurpose;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIREligibilityRequestPurpose
 *
 * @description Code type wrapper for FHIREligibilityRequestPurpose enum
 */
class FHIREligibilityRequestPurposeType extends FHIRCode
{
    public function __construct(
        /** @var FHIREligibilityRequestPurpose|string|null $value The code value */
        public FHIREligibilityRequestPurpose|string|null $value = null,
    ) {
    }
}
