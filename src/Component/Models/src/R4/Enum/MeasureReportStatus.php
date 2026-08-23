<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: MeasureReportStatus
 * URL: http://hl7.org/fhir/ValueSet/measure-report-status
 * Version: 4.0.1
 * Description: The status of the measure report.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/measure-report-status', version: '4.0.1')]
enum MeasureReportStatus: string
{
    /** Complete */
    case complete = 'complete';

    /** Pending */
    case pending = 'pending';

    /** Error */
    case error = 'error';
}
