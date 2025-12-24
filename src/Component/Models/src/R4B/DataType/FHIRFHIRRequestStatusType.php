<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRRequestStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRRequestStatus
 *
 * @description Code type wrapper for FHIRRequestStatus enum
 */
class FHIRFHIRRequestStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRRequestStatus|string|null $value The code value */
        public FHIRFHIRRequestStatus|string|null $value = null,
    ) {
    }
}
