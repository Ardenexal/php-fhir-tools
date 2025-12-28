<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFlagStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRFlagStatus
 *
 * @description Code type wrapper for FHIRFlagStatus enum
 */
class FHIRFlagStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFlagStatus|string|null $value The code value */
        public FHIRFlagStatus|string|null $value = null,
    ) {
    }
}
