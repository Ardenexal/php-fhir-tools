<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Appointment Response Status
 * URL: http://hl7.org/fhir/ValueSet/appointmentresponse-status
 * Version: 5.0.0
 * Description: The Participation status for a participant in response to a request for an appointment.
 */
enum AppointmentResponseStatus: string
{
    /** Accepted */
    case accepted = 'accepted';

    /** Declined */
    case declined = 'declined';

    /** Tentative */
    case tentative = 'tentative';

    /** Needs Action */
    case needsaction = 'needs-action';

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
