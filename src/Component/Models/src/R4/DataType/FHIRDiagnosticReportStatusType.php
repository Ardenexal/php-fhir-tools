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
        /** @param FHIRDiagnosticReportStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
