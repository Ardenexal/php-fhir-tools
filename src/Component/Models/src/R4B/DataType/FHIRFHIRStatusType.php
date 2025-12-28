<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRStatus
 *
 * @description Code type wrapper for FHIRStatus enum
 */
class FHIRFHIRStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRStatus|string|null $value The code value */
        public FHIRFHIRStatus|string|null $value = null,
    ) {
    }
}
