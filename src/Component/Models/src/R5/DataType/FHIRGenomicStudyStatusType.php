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
        /** @var FHIRGenomicStudyStatus|string|null $value The code value */
        public FHIRGenomicStudyStatus|string|null $value = null,
    ) {
    }
}
