<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: SlotStatus
 * URL: http://hl7.org/fhir/ValueSet/slotstatus
 * Version: 4.0.1
 * Description: The free/busy status of the slot.
 */
enum SlotStatus: string
{
    /** Busy */
    case busy = 'busy';

    /** Free */
    case free = 'free';

    /** Busy (Unavailable) */
    case busyunavailable = 'busy-unavailable';

    /** Busy (Tentative) */
    case busytentative = 'busy-tentative';

    /** Entered in error */
    case enteredinerror = 'entered-in-error';
}
