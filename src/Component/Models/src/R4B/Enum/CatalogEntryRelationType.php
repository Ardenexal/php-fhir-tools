<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: CatalogEntryRelationType
 * URL: http://hl7.org/fhir/ValueSet/relation-type
 * Version: 4.3.0
 * Description: The type of relations between entries.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/relation-type', version: '4.3.0')]
enum CatalogEntryRelationType: string
{
    /** Triggers */
    case triggers = 'triggers';

    /** Replaced By */
    case replacedby = 'is-replaced-by';
}
