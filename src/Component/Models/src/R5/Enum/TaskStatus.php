<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Task Status
 * URL: http://hl7.org/fhir/ValueSet/task-status
 * Version: 5.0.0
 * Description: The current status of the task.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/task-status', version: '5.0.0')]
enum TaskStatus: string
{
    /** Draft */
    case draft = 'draft';

    /** Requested */
    case requested = 'requested';

    /** Received */
    case received = 'received';

    /** Accepted */
    case accepted = 'accepted';

    /** Rejected */
    case rejected = 'rejected';

    /** Ready */
    case ready = 'ready';

    /** Cancelled */
    case cancelled = 'cancelled';

    /** In Progress */
    case inprogress = 'in-progress';

    /** On Hold */
    case onhold = 'on-hold';

    /** Failed */
    case failed = 'failed';

    /** Completed */
    case completed = 'completed';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';
}
