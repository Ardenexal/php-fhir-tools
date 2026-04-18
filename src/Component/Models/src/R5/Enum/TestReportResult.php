<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Test Report Result
 * URL: http://hl7.org/fhir/ValueSet/report-result-codes
 * Version: 5.0.0
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
