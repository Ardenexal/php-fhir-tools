<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: ParticipantRequired
 * URL: http://hl7.org/fhir/ValueSet/participantrequired
 * Version: 4.0.1
 * Description: Is the Participant required to attend the appointment.
 */
enum ParticipantRequired: string
{
    /** Required */
    case required = 'required';

    /** Optional */
    case optional = 'optional';

    /** Information Only */
    case informationonly = 'information-only';
}
