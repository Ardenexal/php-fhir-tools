<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: ActionSelectionBehavior
 * URL: http://hl7.org/fhir/ValueSet/action-selection-behavior
 * Version: 4.0.1
 * Description: Defines selection behavior of a group.
 */
enum ActionSelectionBehavior: string
{
    /** Any */
    case any = 'any';

    /** All */
    case all = 'all';

    /** All Or None */
    case allornone = 'all-or-none';

    /** Exactly One */
    case exactlyone = 'exactly-one';

    /** At Most One */
    case atmostone = 'at-most-one';

    /** One Or More */
    case oneormore = 'one-or-more';
}
