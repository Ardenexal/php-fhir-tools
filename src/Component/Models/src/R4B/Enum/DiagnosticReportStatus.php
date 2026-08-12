<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: DiagnosticReportStatus
 * URL: http://hl7.org/fhir/ValueSet/diagnostic-report-status
 * Version: 4.3.0
 * Description: The status of the diagnostic report.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/diagnostic-report-status', version: '4.3.0')]
enum DiagnosticReportStatus: string
{
    /** Registered */
    case registered = 'registered';

    /** Partial */
    case partial = 'partial';

    /** Preliminary */
    case preliminary = 'preliminary';

    /** Final */
    case final = 'final';

    /** Amended */
    case amended = 'amended';

    /** Corrected */
    case corrected = 'corrected';

    /** Appended */
    case appended = 'appended';

    /** Cancelled */
    case cancelled = 'cancelled';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Unknown */
    case unknown = 'unknown';
}
