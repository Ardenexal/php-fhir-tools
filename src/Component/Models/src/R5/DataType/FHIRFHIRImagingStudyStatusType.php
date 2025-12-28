<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRImagingStudyStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRImagingStudyStatus
 *
 * @description Code type wrapper for FHIRImagingStudyStatus enum
 */
class FHIRFHIRImagingStudyStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRImagingStudyStatus|string|null $value The code value */
        public FHIRFHIRImagingStudyStatus|string|null $value = null,
    ) {
    }
}
