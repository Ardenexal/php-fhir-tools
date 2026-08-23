<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Test Report Action Result
 * URL: http://hl7.org/fhir/ValueSet/report-action-result-codes
 * Version: 5.0.0
 * Description: The results of executing an action.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/report-action-result-codes', version: '5.0.0')]
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
