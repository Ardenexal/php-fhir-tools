<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: SortDirection
 * URL: http://hl7.org/fhir/ValueSet/sort-direction
 * Version: 5.0.0
 * Description: The possible sort directions, ascending or descending.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/sort-direction', version: '5.0.0')]
enum SortDirection: string
{
    /** Ascending */
    case ascending = 'ascending';

    /** Descending */
    case descending = 'descending';
}
