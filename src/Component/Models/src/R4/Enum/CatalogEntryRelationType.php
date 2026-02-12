<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: CatalogEntryRelationType
 * URL: http://hl7.org/fhir/ValueSet/relation-type
 * Version: 4.0.1
 * Description: The type of relations between entries.
 */
enum CatalogEntryRelationType: string
{
    /** Triggers */
    case triggers = 'triggers';

    /** Replaced By */
    case replacedby = 'is-replaced-by';
}
