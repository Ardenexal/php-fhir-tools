<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: TestReportResult
 * URL: http://hl7.org/fhir/ValueSet/report-result-codes
 * Version: 4.0.1
 * Description: The reported execution result.
 */
enum TestReportResult: string
{
    /** Pass */
    case pass = 'pass';

    /** Fail */
    case fail = 'fail';

    /** Pending */
    case pending = 'pending';
}
