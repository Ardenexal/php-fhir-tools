<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Assertion Manual Completion Type
 * URL: http://hl7.org/fhir/ValueSet/assert-manual-completion-codes
 * Version: 5.0.0
 * Description: The type of manual completion to use for assertion.
 */
enum FHIRAssertionManualCompletionType: string
{
    /** Fail */
    case fail = 'fail';

    /** Pass */
    case pass = 'pass';

    /** Skip */
    case skip = 'skip';

    /** Stop */
    case stop = 'stop';
}
