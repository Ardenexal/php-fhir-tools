<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: Medication dispense  status  codes
 * URL: http://hl7.org/fhir/ValueSet/medicationdispense-status
 * Version: 4.0.1
 * Description: MedicationDispense Status Codes
 */
enum MedicationDispenseStatusCodes: string
{
    /** Preparation */
    case preparation = 'preparation';

    /** In Progress */
    case inprogress = 'in-progress';

    /** Cancelled */
    case cancelled = 'cancelled';

    /** On Hold */
    case onhold = 'on-hold';

    /** Completed */
    case completed = 'completed';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Stopped */
    case stopped = 'stopped';

    /** Declined */
    case declined = 'declined';

    /** Unknown */
    case unknown = 'unknown';
}
