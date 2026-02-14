<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: Medicationrequest  status
 * URL: http://hl7.org/fhir/ValueSet/medicationrequest-status
 * Version: 4.0.1
 * Description: MedicationRequest Status Codes
 */
enum MedicationrequestStatus: string
{
    /** Active */
    case active = 'active';

    /** On Hold */
    case onhold = 'on-hold';

    /** Cancelled */
    case cancelled = 'cancelled';

    /** Completed */
    case completed = 'completed';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Stopped */
    case stopped = 'stopped';

    /** Draft */
    case draft = 'draft';

    /** Unknown */
    case unknown = 'unknown';
}
