<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRGenomicStudyStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRGenomicStudyStatus
 *
 * @description Code type wrapper for FHIRGenomicStudyStatus enum
 */
class FHIRFHIRGenomicStudyStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRGenomicStudyStatus|string|null $value The code value */
        public FHIRFHIRGenomicStudyStatus|string|null $value = null,
    ) {
    }
}
