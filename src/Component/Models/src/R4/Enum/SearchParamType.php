<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: SearchParamType
 * URL: http://hl7.org/fhir/ValueSet/search-param-type
 * Version: 4.0.1
 * Description: Data types allowed to be used for search parameters.
 */
enum SearchParamType: string
{
    /** Number */
    case number = 'number';

    /** Date/DateTime */
    case datedatetime = 'date';

    /** String */
    case string = 'string';

    /** Token */
    case token = 'token';

    /** Reference */
    case reference = 'reference';

    /** Composite */
    case composite = 'composite';

    /** Quantity */
    case quantity = 'quantity';

    /** URI */
    case uri = 'uri';

    /** Special */
    case special = 'special';
}
