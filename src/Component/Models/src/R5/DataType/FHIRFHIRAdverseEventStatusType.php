<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRAdverseEventStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAdverseEventStatus
 *
 * @description Code type wrapper for FHIRAdverseEventStatus enum
 */
class FHIRFHIRAdverseEventStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAdverseEventStatus|string|null $value The code value */
        public FHIRFHIRAdverseEventStatus|string|null $value = null,
    ) {
    }
}
