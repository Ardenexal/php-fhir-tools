<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Assertion Manual Completion Type
 * URL: http://hl7.org/fhir/ValueSet/assert-manual-completion-codes
 * Version: 5.0.0
 * Description: The type of manual completion to use for assertion.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/assert-manual-completion-codes', version: '5.0.0')]
enum AssertionManualCompletionType: string
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
