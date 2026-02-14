<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: SearchComparator
 * URL: http://hl7.org/fhir/ValueSet/search-comparator
 * Version: 4.0.1
 * Description: What Search Comparator Codes are supported in search.
 */
enum SearchComparator: string
{
    /** Equals */
    case equals = 'eq';

    /** Not Equals */
    case notequals = 'ne';

    /** Greater Than */
    case greaterthan = 'gt';

    /** Less Than */
    case lessthan = 'lt';

    /** Greater or Equals */
    case greaterorequals = 'ge';

    /** Less of Equal */
    case lessofequal = 'le';

    /** Starts After */
    case startsafter = 'sa';

    /** Ends Before */
    case endsbefore = 'eb';

    /** Approximately */
    case approximately = 'ap';
}
