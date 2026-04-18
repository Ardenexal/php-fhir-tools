<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Naming System Identifier Type
 * URL: http://hl7.org/fhir/ValueSet/namingsystem-identifier-type
 * Version: 5.0.0
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

    /** IRI stem */
    case iristem = 'iri-stem';

    /** V2CSMNemonic */
    case v2_csmnemonic = 'v2csmnemonic';

    /** Other */
    case other = 'other';
}
