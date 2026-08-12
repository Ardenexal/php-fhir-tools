<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Participation Status
 * URL: http://hl7.org/fhir/ValueSet/participationstatus
 * Version: 5.0.0
 * Description: The Participation status of an appointment.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/participationstatus', version: '5.0.0')]
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
