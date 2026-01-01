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
        /** @param FHIRTransportStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
