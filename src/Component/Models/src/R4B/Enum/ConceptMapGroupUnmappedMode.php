<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: ConceptMapGroupUnmappedMode
 * URL: http://hl7.org/fhir/ValueSet/conceptmap-unmapped-mode
 * Version: 4.3.0
 * Description: Defines which action to take if there is no match in the group.
 */
enum ConceptMapGroupUnmappedMode: string
{
    /** Provided Code */
    case providedcode = 'provided';

    /** Fixed Code */
    case fixedcode = 'fixed';

    /** Other Map */
    case othermap = 'other-map';
}
