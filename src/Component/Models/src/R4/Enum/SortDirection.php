<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: SortDirection
 * URL: http://hl7.org/fhir/ValueSet/sort-direction
 * Version: 4.0.1
 * Description: The possible sort directions, ascending or descending.
 */
enum SortDirection: string
{
    /** Ascending */
    case ascending = 'ascending';

    /** Descending */
    case descending = 'descending';
}
