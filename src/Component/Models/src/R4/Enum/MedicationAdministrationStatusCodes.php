<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: Medication administration  status  codes
 * URL: http://hl7.org/fhir/ValueSet/medication-admin-status
 * Version: 4.0.1
 * Description: MedicationAdministration Status Codes
 */
enum MedicationAdministrationStatusCodes: string
{
    /** In Progress */
    case inprogress = 'in-progress';

    /** Not Done */
    case notdone = 'not-done';

    /** On Hold */
    case onhold = 'on-hold';

    /** Completed */
    case completed = 'completed';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Stopped */
    case stopped = 'stopped';

    /** Unknown */
    case unknown = 'unknown';
}
