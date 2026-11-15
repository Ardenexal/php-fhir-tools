<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Adverse Event Status
 * URL: http://hl7.org/fhir/ValueSet/adverse-event-status
 * Version: 5.0.0
 * Description: Codes identifying the lifecycle stage of an adverse event.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/adverse-event-status', version: '5.0.0')]
enum AdverseEventStatus: string
{
    /** Preparation */
    case preparation = 'preparation';

    /** In Progress */
    case inprogress = 'in-progress';

    /** Not Done */
    case notdone = 'not-done';

    /** On Hold */
    case onhold = 'on-hold';

    /** Stopped */
    case stopped = 'stopped';

    /** Completed */
    case completed = 'completed';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Unknown */
    case unknown = 'unknown';
}
