<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIREventStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIREventStatus
 *
 * @description Code type wrapper for FHIREventStatus enum
 */
class FHIREventStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIREventStatus|string|null $value The code value */
        public FHIREventStatus|string|null $value = null,
    ) {
    }
}
