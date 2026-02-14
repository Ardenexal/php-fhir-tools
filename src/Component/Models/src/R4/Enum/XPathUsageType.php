<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: XPathUsageType
 * URL: http://hl7.org/fhir/ValueSet/search-xpath-usage
 * Version: 4.0.1
 * Description: How a search parameter relates to the set of elements returned by evaluating its xpath query.
 */
enum XPathUsageType: string
{
    /** Normal */
    case normal = 'normal';

    /** Phonetic */
    case phonetic = 'phonetic';

    /** Nearby */
    case nearby = 'nearby';

    /** Distance */
    case distance = 'distance';

    /** Other */
    case other = 'other';
}
