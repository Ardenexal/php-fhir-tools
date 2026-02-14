<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: Immunization Status Codes
 * URL: http://hl7.org/fhir/ValueSet/immunization-status
 * Version: 4.0.1
 * Description: The value set to instantiate this attribute should be drawn from a terminologically robust code system that consists of or contains concepts to support describing the current status of the administered dose of vaccine.
 */
enum ImmunizationStatusCodes: string
{
    /** Preparation */
    case preparation = 'preparation';

    /** In Progress */
    case inprogress = 'in-progress';

    /** Not Done */
    case notdone = 'not-done';

    /** On Hold */
    case onhold = 'on-hold';

    /** Stopped */
    case stopped = 'stopped';

    /** Completed */
    case completed = 'completed';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Unknown */
    case unknown = 'unknown';
}
