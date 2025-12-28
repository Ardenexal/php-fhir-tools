<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRMeasureReportStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRMeasureReportStatus
 *
 * @description Code type wrapper for FHIRMeasureReportStatus enum
 */
class FHIRMeasureReportStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRMeasureReportStatus|string|null $value The code value */
        public FHIRMeasureReportStatus|string|null $value = null,
    ) {
    }
}
