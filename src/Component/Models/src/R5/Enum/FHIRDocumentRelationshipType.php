<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: DocumentRelationshipType
 * URL: http://hl7.org/fhir/ValueSet/document-relationship-type
 * Version: 4.0.1
 * Description: The type of relationship between documents.
 */
enum FHIRDocumentRelationshipType: string
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
