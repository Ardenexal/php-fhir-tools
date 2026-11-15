<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Action Participant Type
 * URL: http://hl7.org/fhir/ValueSet/action-participant-type
 * Version: 5.0.0
 * Description: The type of participant for the action.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/action-participant-type', version: '5.0.0')]
enum ActionParticipantType: string
{
    /** CareTeam */
    case careteam = 'careteam';

    /** Device */
    case device = 'device';

    /** Group */
    case group = 'group';

    /** HealthcareService */
    case healthcareservice = 'healthcareservice';

    /** Location */
    case location = 'location';

    /** Organization */
    case organization = 'organization';

    /** Patient */
    case patient = 'patient';

    /** Practitioner */
    case practitioner = 'practitioner';

    /** PractitionerRole */
    case practitionerrole = 'practitionerrole';

    /** RelatedPerson */
    case relatedperson = 'relatedperson';
}
