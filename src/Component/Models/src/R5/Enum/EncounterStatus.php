<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Encounter Status
 * URL: http://hl7.org/fhir/ValueSet/encounter-status
 * Version: 5.0.0
 * Description: Current state of the encounter.
 */
enum EncounterStatus: string
{
    /** Planned */
    case planned = 'planned';

    /** In Progress */
    case inprogress = 'in-progress';

    /** On Hold */
    case onhold = 'on-hold';

    /** Discharged */
    case discharged = 'discharged';

    /** Completed */
    case completed = 'completed';

    /** Cancelled */
    case cancelled = 'cancelled';

    /** Discontinued */
    case discontinued = 'discontinued';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Unknown */
    case unknown = 'unknown';
}
