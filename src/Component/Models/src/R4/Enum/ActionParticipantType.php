<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ActionParticipantType
 * URL: http://hl7.org/fhir/ValueSet/action-participant-type
 * Version: 4.0.1
 * Description: The type of participant for the action.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/action-participant-type', version: '4.0.1')]
enum ActionParticipantType: string
{
    /** Patient */
    case patient = 'patient';

    /** Practitioner */
    case practitioner = 'practitioner';

    /** Related Person */
    case relatedperson = 'related-person';

    /** Device */
    case device = 'device';
}
