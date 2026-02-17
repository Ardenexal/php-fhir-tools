<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: CareTeamStatus
 * URL: http://hl7.org/fhir/ValueSet/care-team-status
 * Version: 4.0.1
 * Description: Indicates the status of the care team.
 */
enum CareTeamStatus: string
{
    /** Proposed */
    case proposed = 'proposed';

    /** Active */
    case active = 'active';

    /** Suspended */
    case suspended = 'suspended';

    /** Inactive */
    case inactive = 'inactive';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';
}
