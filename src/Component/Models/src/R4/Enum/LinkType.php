<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: LinkType
 * URL: http://hl7.org/fhir/ValueSet/link-type
 * Version: 4.0.1
 * Description: The type of link between this patient resource and another patient resource.
 */
enum LinkType: string
{
    /** Replaced-by */
    case replacedby = 'replaced-by';

    /** Replaces */
    case replaces = 'replaces';

    /** Refer */
    case refer = 'refer';

    /** See also */
    case seealso = 'seealso';
}
