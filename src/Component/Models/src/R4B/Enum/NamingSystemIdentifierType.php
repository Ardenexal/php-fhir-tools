<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: NamingSystemIdentifierType
 * URL: http://hl7.org/fhir/ValueSet/namingsystem-identifier-type
 * Version: 4.3.0
 * Description: Identifies the style of unique identifier used to identify a namespace.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/namingsystem-identifier-type', version: '4.3.0')]
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
