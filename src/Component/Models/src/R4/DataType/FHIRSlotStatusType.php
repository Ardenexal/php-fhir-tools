<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRSlotStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSlotStatus
 *
 * @description Code type wrapper for FHIRSlotStatus enum
 */
class FHIRSlotStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRSlotStatus|string|null $value The code value */
        public FHIRSlotStatus|string|null $value = null,
    ) {
    }
}
