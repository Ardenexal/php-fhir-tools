<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: SearchModifierCode
 * URL: http://hl7.org/fhir/ValueSet/search-modifier-code
 * Version: 4.3.0
 * Description: A supported modifier for a search parameter.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/search-modifier-code', version: '4.3.0')]
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
    case oftype = 'ofType';
}
