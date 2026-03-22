<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: GoalLifecycleStatus
 * URL: http://hl7.org/fhir/ValueSet/goal-status
 * Version: 4.0.1
 * Description: Codes that reflect the current state of a goal and whether the goal is still being targeted.
 */
enum GoalLifecycleStatus: string
{
    /** Proposed */
    case proposed = 'proposed';

    /** Planned */
    case planned = 'planned';

    /** Accepted */
    case accepted = 'accepted';

    /** Cancelled */
    case cancelled = 'cancelled';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Rejected */
    case rejected = 'rejected';
}
