<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRTestReportStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRTestReportStatus
 *
 * @description Code type wrapper for FHIRTestReportStatus enum
 */
class FHIRFHIRTestReportStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRTestReportStatus|string|null $value The code value */
        public FHIRFHIRTestReportStatus|string|null $value = null,
    ) {
    }
}
