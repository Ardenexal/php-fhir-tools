<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: CarePlanActivityStatus
 * URL: http://hl7.org/fhir/ValueSet/care-plan-activity-status
 * Version: 4.0.1
 * Description: Codes that reflect the current state of a care plan activity within its overall life cycle.
 */
enum CarePlanActivityStatus: string
{
    /** Not Started */
    case notstarted = 'not-started';

    /** Scheduled */
    case scheduled = 'scheduled';

    /** In Progress */
    case inprogress = 'in-progress';

    /** On Hold */
    case onhold = 'on-hold';

    /** Completed */
    case completed = 'completed';

    /** Cancelled */
    case cancelled = 'cancelled';

    /** Unknown */
    case unknown = 'unknown';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';
}
