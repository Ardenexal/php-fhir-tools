<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Diagnostic Report Status
 * URL: http://hl7.org/fhir/ValueSet/diagnostic-report-status
 * Version: 5.0.0
 * Description: The status of the diagnostic report.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/diagnostic-report-status', version: '5.0.0')]
enum DiagnosticReportStatus: string
{
    /** Registered */
    case registered = 'registered';

    /** Partial */
    case partial = 'partial';

    /** Preliminary */
    case preliminary = 'preliminary';

    /** Modified */
    case modified = 'modified';

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
