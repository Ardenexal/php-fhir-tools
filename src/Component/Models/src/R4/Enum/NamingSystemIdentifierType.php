<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: NamingSystemIdentifierType
 * URL: http://hl7.org/fhir/ValueSet/namingsystem-identifier-type
 * Version: 4.0.1
 * Description: Identifies the style of unique identifier used to identify a namespace.
 */
enum NamingSystemIdentifierType: string
{
    /** OID */
    case oid = 'oid';

    /** UUID */
    case uuid = 'uuid';

    /** URI */
    case uri = 'uri';

    /** Other */
    case other = 'other';
}
