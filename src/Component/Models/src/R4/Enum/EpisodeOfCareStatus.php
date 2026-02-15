<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: EpisodeOfCareStatus
 * URL: http://hl7.org/fhir/ValueSet/episode-of-care-status
 * Version: 4.0.1
 * Description: The status of the episode of care.
 */
enum EpisodeOfCareStatus: string
{
    /** Planned */
    case planned = 'planned';

    /** Waitlist */
    case waitlist = 'waitlist';

    /** Active */
    case active = 'active';

    /** On Hold */
    case onhold = 'onhold';

    /** Finished */
    case finished = 'finished';

    /** Cancelled */
    case cancelled = 'cancelled';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';
}
