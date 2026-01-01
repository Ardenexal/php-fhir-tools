<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRGenomicStudyStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRGenomicStudyStatus
 *
 * @description Code type wrapper for FHIRGenomicStudyStatus enum
 */
class FHIRGenomicStudyStatusType extends FHIRCode
{
    public function __construct(
        /** @param FHIRGenomicStudyStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
