<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: XPathUsageType
 * URL: http://hl7.org/fhir/ValueSet/search-xpath-usage
 * Version: 4.3.0
 * Description: How a search parameter relates to the set of elements returned by evaluating its xpath query.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/search-xpath-usage', version: '4.3.0')]
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
