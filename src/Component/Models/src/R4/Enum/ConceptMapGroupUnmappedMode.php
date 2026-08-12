<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ConceptMapGroupUnmappedMode
 * URL: http://hl7.org/fhir/ValueSet/conceptmap-unmapped-mode
 * Version: 4.0.1
 * Description: Defines which action to take if there is no match in the group.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/conceptmap-unmapped-mode', version: '4.0.1')]
enum ConceptMapGroupUnmappedMode: string
{
    /** Provided Code */
    case providedcode = 'provided';

    /** Fixed Code */
    case fixedcode = 'fixed';

    /** Other Map */
    case othermap = 'other-map';
}
