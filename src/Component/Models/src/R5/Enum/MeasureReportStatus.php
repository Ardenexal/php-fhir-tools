<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Measure Report Status
 * URL: http://hl7.org/fhir/ValueSet/measure-report-status
 * Version: 5.0.0
 * Description: The status of the measure report.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/measure-report-status', version: '5.0.0')]
enum MeasureReportStatus: string
{
    /** Complete */
    case complete = 'complete';

    /** Pending */
    case pending = 'pending';

    /** Error */
    case error = 'error';
}
