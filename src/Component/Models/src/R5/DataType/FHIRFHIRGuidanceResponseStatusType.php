<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRGuidanceResponseStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRGuidanceResponseStatus
 *
 * @description Code type wrapper for FHIRGuidanceResponseStatus enum
 */
class FHIRFHIRGuidanceResponseStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRGuidanceResponseStatus|string|null $value The code value */
        public FHIRFHIRGuidanceResponseStatus|string|null $value = null,
    ) {
    }
}
