<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ConceptMapRelationship
 * URL: http://hl7.org/fhir/ValueSet/concept-map-relationship
 * Version: 5.0.0
 * Description: The relationship between concepts.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/concept-map-relationship', version: '5.0.0')]
enum ConceptMapRelationship: string
{
    /** Related To */
    case relatedto = 'related-to';

    /** Equivalent */
    case equivalent = 'equivalent';

    /** Source Is Narrower Than Target */
    case sourceisnarrowerthantarget = 'source-is-narrower-than-target';

    /** Source Is Broader Than Target */
    case sourceisbroaderthantarget = 'source-is-broader-than-target';

    /** Not Related To */
    case notrelatedto = 'not-related-to';
}
