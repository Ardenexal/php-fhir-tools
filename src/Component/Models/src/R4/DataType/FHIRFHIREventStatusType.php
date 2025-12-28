<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIREventStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIREventStatus
 *
 * @description Code type wrapper for FHIREventStatus enum
 */
class FHIRFHIREventStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIREventStatus|string|null $value The code value */
        public FHIRFHIREventStatus|string|null $value = null,
    ) {
    }
}
