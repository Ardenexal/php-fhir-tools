<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: SearchEntryMode
 * URL: http://hl7.org/fhir/ValueSet/search-entry-mode
 * Version: 4.0.1
 * Description: Why an entry is in the result set - whether it's included as a match or because of an _include requirement, or to convey information or warning information about the search process.
 */
enum SearchEntryMode: string
{
    /** Match */
    case match = 'match';

    /** Include */
    case include = 'include';

    /** Outcome */
    case outcome = 'outcome';
}
