<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRListStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRListStatus
 *
 * @description Code type wrapper for FHIRListStatus enum
 */
class FHIRFHIRListStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRListStatus|string|null $value The code value */
        public FHIRFHIRListStatus|string|null $value = null,
    ) {
    }
}
