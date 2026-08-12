<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Concept Map Equivalence Value Set
 * URL: http://hl7.org/fhir/ValueSet/concept-map-equivalence
 * Version: 5.3.0
 * Description: The degree of equivalence between concepts.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/concept-map-equivalence', version: '5.3.0')]
enum ConceptMapEquivalence: string
{
    /** Related To */
    case relatedto = 'relatedto';

    /** Equivalent */
    case equivalent = 'equivalent';

    /** Equal */
    case equal = 'equal';

    /** Wider */
    case wider = 'wider';

    /** Subsumes */
    case subsumes = 'subsumes';

    /** Narrower */
    case narrower = 'narrower';

    /** Specializes */
    case specializes = 'specializes';

    /** Inexact */
    case inexact = 'inexact';

    /** Unmatched */
    case unmatched = 'unmatched';

    /** Disjoint */
    case disjoint = 'disjoint';
}
