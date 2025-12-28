<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRTransportStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRTransportStatus
 *
 * @description Code type wrapper for FHIRTransportStatus enum
 */
class FHIRFHIRTransportStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRTransportStatus|string|null $value The code value */
        public FHIRFHIRTransportStatus|string|null $value = null,
    ) {
    }
}
