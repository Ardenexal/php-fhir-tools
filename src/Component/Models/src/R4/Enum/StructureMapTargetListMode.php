<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: StructureMapTargetListMode
 * URL: http://hl7.org/fhir/ValueSet/map-target-list-mode
 * Version: 4.0.1
 * Description: If field is a list, how to manage the production.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/map-target-list-mode', version: '4.0.1')]
enum StructureMapTargetListMode: string
{
    /** First */
    case first = 'first';

    /** Share */
    case share = 'share';

    /** Last */
    case last = 'last';

    /** Collate */
    case collate = 'collate';
}
