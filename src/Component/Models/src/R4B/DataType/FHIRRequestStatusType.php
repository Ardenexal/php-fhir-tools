<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRRequestStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRRequestStatus
 *
 * @description Code type wrapper for FHIRRequestStatus enum
 */
class FHIRRequestStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRRequestStatus|string|null $value The code value */
        public FHIRRequestStatus|string|null $value = null,
    ) {
    }
}
