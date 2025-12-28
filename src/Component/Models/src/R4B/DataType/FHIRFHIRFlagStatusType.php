<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRFlagStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRFlagStatus
 *
 * @description Code type wrapper for FHIRFlagStatus enum
 */
class FHIRFHIRFlagStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRFlagStatus|string|null $value The code value */
        public FHIRFHIRFlagStatus|string|null $value = null,
    ) {
    }
}
