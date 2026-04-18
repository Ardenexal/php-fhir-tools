<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Search Modifier Code
 * URL: http://hl7.org/fhir/ValueSet/search-modifier-code
 * Version: 5.0.0
 * Description: A supported modifier for a search parameter.
 */
enum SearchModifierCode: string
{
    /** Missing */
    case missing = 'missing';

    /** Exact */
    case exact = 'exact';

    /** Contains */
    case contains = 'contains';

    /** Not */
    case not = 'not';

    /** Text */
    case text = 'text';

    /** In */
    case in = 'in';

    /** Not In */
    case notin = 'not-in';

    /** Below */
    case below = 'below';

    /** Above */
    case above = 'above';

    /** Type */
    case type = 'type';

    /** Identifier */
    case identifier = 'identifier';

    /** Of Type */
    case oftype = 'of-type';

    /** Code Text */
    case codetext = 'code-text';

    /** Text Advanced */
    case textadvanced = 'text-advanced';

    /** Iterate */
    case iterate = 'iterate';
}
