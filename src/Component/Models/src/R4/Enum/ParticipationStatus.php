<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: ParticipationStatus
 * URL: http://hl7.org/fhir/ValueSet/participationstatus
 * Version: 4.0.1
 * Description: The Participation status of an appointment.
 */
enum ParticipationStatus: string
{
    /** Accepted */
    case accepted = 'accepted';

    /** Declined */
    case declined = 'declined';

    /** Tentative */
    case tentative = 'tentative';

    /** Needs Action */
    case needsaction = 'needs-action';
}
