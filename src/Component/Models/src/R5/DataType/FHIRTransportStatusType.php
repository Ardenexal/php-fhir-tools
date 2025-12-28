<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRTransportStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRTransportStatus
 *
 * @description Code type wrapper for FHIRTransportStatus enum
 */
class FHIRTransportStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRTransportStatus|string|null $value The code value */
        public FHIRTransportStatus|string|null $value = null,
    ) {
    }
}
