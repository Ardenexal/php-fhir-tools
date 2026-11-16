<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Concept Map Group Unmapped Mode
 * URL: http://hl7.org/fhir/ValueSet/conceptmap-unmapped-mode
 * Version: 5.0.0
 * Description: Defines which action to take if there is no match in the group.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/conceptmap-unmapped-mode', version: '5.0.0')]
enum ConceptMapGroupUnmappedMode: string
{
    /** Use Provided Source Code */
    case useprovidedsourcecode = 'use-source-code';

    /** Fixed Code */
    case fixedcode = 'fixed';

    /** Other Map */
    case othermap = 'other-map';
}
