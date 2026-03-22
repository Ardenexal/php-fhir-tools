<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: RelatedArtifactType
 * URL: http://hl7.org/fhir/ValueSet/related-artifact-type
 * Version: 4.0.1
 * Description: The type of relationship to the related artifact.
 */
enum RelatedArtifactType: string
{
    /** Documentation */
    case documentation = 'documentation';

    /** Justification */
    case justification = 'justification';

    /** Citation */
    case citation = 'citation';

    /** Predecessor */
    case predecessor = 'predecessor';

    /** Successor */
    case successor = 'successor';

    /** Derived From */
    case derivedfrom = 'derived-from';

    /** Depends On */
    case dependson = 'depends-on';

    /** Composed Of */
    case composedof = 'composed-of';
}
