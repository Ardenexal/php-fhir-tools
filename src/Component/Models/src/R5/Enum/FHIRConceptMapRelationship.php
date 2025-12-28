<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: ConceptMapRelationship
 * URL: http://hl7.org/fhir/ValueSet/concept-map-relationship
 * Version: 5.0.0
 * Description: The relationship between concepts.
 */
enum FHIRConceptMapRelationship: string
{
    /** Related To */
    case relatedto = 'related-to';

    /** Not Related To */
    case notrelatedto = 'not-related-to';
}
