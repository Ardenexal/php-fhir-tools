<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: ConceptMapEquivalence
 * URL: http://hl7.org/fhir/ValueSet/concept-map-equivalence
 * Version: 4.0.1
 * Description: The degree of equivalence between concepts.
 */
enum ConceptMapEquivalence: string
{
    /** Related To */
    case relatedto = 'relatedto';

    /** Unmatched */
    case unmatched = 'unmatched';
}
