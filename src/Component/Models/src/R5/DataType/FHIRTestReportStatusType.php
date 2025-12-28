<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRTestReportStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRTestReportStatus
 *
 * @description Code type wrapper for FHIRTestReportStatus enum
 */
class FHIRTestReportStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRTestReportStatus|string|null $value The code value */
        public FHIRTestReportStatus|string|null $value = null,
    ) {
    }
}
