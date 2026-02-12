<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: TestReportActionResult
 * URL: http://hl7.org/fhir/ValueSet/report-action-result-codes
 * Version: 4.0.1
 * Description: The results of executing an action.
 */
enum TestReportActionResult: string
{
    /** Pass */
    case pass = 'pass';

    /** Skip */
    case skip = 'skip';

    /** Fail */
    case fail = 'fail';

    /** Warning */
    case warning = 'warning';

    /** Error */
    case error = 'error';
}
