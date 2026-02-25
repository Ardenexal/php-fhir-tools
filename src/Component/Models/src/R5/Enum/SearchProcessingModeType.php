<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Search Processing Mode Type
 * URL: http://hl7.org/fhir/ValueSet/search-processingmode
 * Version: 5.0.0
 * Description: How a search parameter relates to the set of elements returned by evaluating its expression query.
 */
enum SearchProcessingModeType: string
{
    /** Normal */
    case normal = 'normal';

    /** Phonetic */
    case phonetic = 'phonetic';

    /** Other */
    case other = 'other';
}
