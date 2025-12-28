<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDiagnosticReportStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDiagnosticReportStatus
 *
 * @description Code type wrapper for FHIRDiagnosticReportStatus enum
 */
class FHIRDiagnosticReportStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRDiagnosticReportStatus|string|null $value The code value */
        public FHIRDiagnosticReportStatus|string|null $value = null,
    ) {
    }
}
