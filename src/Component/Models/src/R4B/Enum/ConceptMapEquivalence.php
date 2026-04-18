<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: Concept Map Equivalence Value Set
 * URL: http://hl7.org/fhir/ValueSet/concept-map-equivalence
 * Version: 5.3.0-ballot
 * Description: The degree of equivalence between concepts.
 */
enum ConceptMapEquivalence: string
{
    /** Related To */
    case relatedto = 'relatedto';

    /** Unmatched */
    case unmatched = 'unmatched';
}
