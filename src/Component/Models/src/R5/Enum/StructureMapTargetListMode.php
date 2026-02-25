<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Structure Map Target List Mode
 * URL: http://hl7.org/fhir/ValueSet/map-target-list-mode
 * Version: 5.0.0
 * Description: If field is a list, how to manage the production.
 */
enum StructureMapTargetListMode: string
{
    /** First */
    case first = 'first';

    /** Share */
    case share = 'share';

    /** Last */
    case last = 'last';

    /** single */
    case single = 'single';
}
