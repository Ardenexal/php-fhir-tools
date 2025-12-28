<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDiagnosticReportStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDiagnosticReportStatus
 *
 * @description Code type wrapper for FHIRDiagnosticReportStatus enum
 */
class FHIRFHIRDiagnosticReportStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRDiagnosticReportStatus|string|null $value The code value */
        public FHIRFHIRDiagnosticReportStatus|string|null $value = null,
    ) {
    }
}
