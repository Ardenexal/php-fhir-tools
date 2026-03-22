<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: EncounterStatus
 * URL: http://hl7.org/fhir/ValueSet/encounter-status
 * Version: 4.0.1
 * Description: Current state of the encounter.
 */
enum EncounterStatus: string
{
    /** Planned */
    case planned = 'planned';

    /** Arrived */
    case arrived = 'arrived';

    /** Triaged */
    case triaged = 'triaged';

    /** In Progress */
    case inprogress = 'in-progress';

    /** On Leave */
    case onleave = 'onleave';

    /** Finished */
    case finished = 'finished';

    /** Cancelled */
    case cancelled = 'cancelled';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Unknown */
    case unknown = 'unknown';
}
