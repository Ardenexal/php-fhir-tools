<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Detected Issue Status
 * URL: http://hl7.org/fhir/ValueSet/detectedissue-status
 * Version: 5.0.0
 * Description: Indicates the status of a detected issue
 */
enum DetectedIssueStatus: string
{
    /** Registered */
    case registered = 'registered';

    /** Preliminary */
    case preliminary = 'preliminary';

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

    /** Mitigated */
    case mitigated = 'mitigated';
}
