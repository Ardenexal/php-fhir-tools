<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSlotStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSlotStatus
 *
 * @description Code type wrapper for FHIRSlotStatus enum
 */
class FHIRFHIRSlotStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRSlotStatus|string|null $value The code value */
        public FHIRFHIRSlotStatus|string|null $value = null,
    ) {
    }
}
