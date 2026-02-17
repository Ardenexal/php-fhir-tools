<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: Medication  status  codes
 * URL: http://hl7.org/fhir/ValueSet/medication-statement-status
 * Version: 4.0.1
 * Description: Medication Status Codes
 */
enum MedicationStatusCodes: string
{
    /** Active */
    case active = 'active';

    /** Completed */
    case completed = 'completed';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Intended */
    case intended = 'intended';

    /** Stopped */
    case stopped = 'stopped';

    /** On Hold */
    case onhold = 'on-hold';

    /** Unknown */
    case unknown = 'unknown';

    /** Not Taken */
    case nottaken = 'not-taken';
}
