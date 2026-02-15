<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: DiagnosticReportStatus
 * URL: http://hl7.org/fhir/ValueSet/diagnostic-report-status
 * Version: 4.0.1
 * Description: The status of the diagnostic report.
 */
enum DiagnosticReportStatus: string
{
    /** Registered */
    case registered = 'registered';

    /** Partial */
    case partial = 'partial';

    /** Final */
    case final = 'final';

    /** Amended */
    case amended = 'amended';

    /** Cancelled */
    case cancelled = 'cancelled';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Unknown */
    case unknown = 'unknown';
}
