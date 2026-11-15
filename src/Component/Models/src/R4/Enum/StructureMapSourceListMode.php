<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: StructureMapSourceListMode
 * URL: http://hl7.org/fhir/ValueSet/map-source-list-mode
 * Version: 4.0.1
 * Description: If field is a list, how to manage the source.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/map-source-list-mode', version: '4.0.1')]
enum StructureMapSourceListMode: string
{
    /** First */
    case first = 'first';

    /** All but the first */
    case allbutthefirst = 'not_first';

    /** Last */
    case last = 'last';

    /** All but the last */
    case allbutthelast = 'not_last';

    /** Enforce only one */
    case enforceonlyone = 'only_one';
}
