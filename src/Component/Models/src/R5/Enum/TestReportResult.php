<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Test Report Result
 * URL: http://hl7.org/fhir/ValueSet/report-result-codes
 * Version: 5.0.0
 * Description: The reported execution result.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/report-result-codes', version: '5.0.0')]
enum TestReportResult: string
{
    /** Pass */
    case pass = 'pass';

    /** Fail */
    case fail = 'fail';

    /** Pending */
    case pending = 'pending';
}
