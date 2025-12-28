<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRMeasureReportStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRMeasureReportStatus
 *
 * @description Code type wrapper for FHIRMeasureReportStatus enum
 */
class FHIRFHIRMeasureReportStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRMeasureReportStatus|string|null $value The code value */
        public FHIRFHIRMeasureReportStatus|string|null $value = null,
    ) {
    }
}
