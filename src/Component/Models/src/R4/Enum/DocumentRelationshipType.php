<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: DocumentRelationshipType
 * URL: http://hl7.org/fhir/ValueSet/document-relationship-type
 * Version: 4.0.1
 * Description: The type of relationship between documents.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/document-relationship-type', version: '4.0.1')]
enum DocumentRelationshipType: string
{
    /** Replaces */
    case replaces = 'replaces';

    /** Transforms */
    case transforms = 'transforms';

    /** Signs */
    case signs = 'signs';

    /** Appends */
    case appends = 'appends';
}
