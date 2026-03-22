<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: AppointmentStatus
 * URL: http://hl7.org/fhir/ValueSet/appointmentstatus
 * Version: 4.0.1
 * Description: The free/busy status of an appointment.
 */
enum AppointmentStatus: string
{
    /** Proposed */
    case proposed = 'proposed';

    /** Pending */
    case pending = 'pending';

    /** Booked */
    case booked = 'booked';

    /** Arrived */
    case arrived = 'arrived';

    /** Fulfilled */
    case fulfilled = 'fulfilled';

    /** Cancelled */
    case cancelled = 'cancelled';

    /** No Show */
    case noshow = 'noshow';

    /** Entered in error */
    case enteredinerror = 'entered-in-error';

    /** Checked In */
    case checkedin = 'checked-in';

    /** Waitlisted */
    case waitlisted = 'waitlist';
}
