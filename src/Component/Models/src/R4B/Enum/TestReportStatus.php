<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: TestReportStatus
 * URL: http://hl7.org/fhir/ValueSet/report-status-codes
 * Version: 4.3.0
 * Description: The current status of the test report.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/report-status-codes', version: '4.3.0')]
enum TestReportStatus: string
{
    /** Completed */
    case completed = 'completed';

    /** In Progress */
    case inprogress = 'in-progress';

    /** Waiting */
    case waiting = 'waiting';

    /** Stopped */
    case stopped = 'stopped';

    /** Entered In Error */
    case enteredinerror = 'entered-in-error';
}
