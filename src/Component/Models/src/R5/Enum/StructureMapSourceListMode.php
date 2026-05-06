<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Structure Map Source List Mode
 * URL: http://hl7.org/fhir/ValueSet/map-source-list-mode
 * Version: 5.0.0
 * Description: If field is a list, how to manage the source.
 */
enum StructureMapSourceListMode: string
{
    /** First */
    case first = 'first';

    /** All but the first */
    case allbutthefirst = 'not_first';

    /** Last */
    case last = 'last';

    /** All but the last */
    case allbutthelast = 'not_last';

    /** Enforce only one */
    case enforceonlyone = 'only_one';
}
