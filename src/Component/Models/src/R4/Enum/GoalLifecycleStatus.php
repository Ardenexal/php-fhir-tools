<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: GoalLifecycleStatus
 * URL: http://hl7.org/fhir/ValueSet/goal-status
 * Version: 4.0.1
 * Description: Codes that reflect the current state of a goal and whether the goal is still being targeted.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/goal-status', version: '4.0.1')]
enum GoalLifecycleStatus: string
{
    /** Proposed */
    case proposed = 'proposed';

    /** Planned */
    case planned = 'planned';

    /** Accepted */
    case accepted = 'accepted';

    /** Active */
    case active = 'active';

    /** On Hold */
    case onhold = 'on-hold';

    /** Completed */
    case completed = 'completed';

    /** Cancelled */
    case cancelled = 'cancelled';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Rejected */
    case rejected = 'rejected';
}
